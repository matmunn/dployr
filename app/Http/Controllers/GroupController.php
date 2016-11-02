<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Group;
use App\Http\Requests;
use App\Models\Server;
use App\Models\Repository;
use App\Models\Environment;
use Illuminate\Http\Request;
use App\Jobs\SendRegistrationInvite;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function userRequired()
    {
        return view('group.userRequired');
    }

    public function saveUserRequired(Request $request)
    {
        $this->validate(
            $request,
            [
                'name' => 'required|string'
            ]
        );

        $group = new Group;
        $group->group_name = $request->name;
        $group->admin_user = Auth::user()->id;
        if (Auth::user()->plan_id) {
            $group->plan_id = Auth::user()->plan_id;
        } else {
            $group->plan_id = 1;
        }
        $group->save();
        Auth::user()->attachRole(Role::where('name', 'owner')->first());
        $group->users()->save(Auth::user());

        foreach (Repository::where('user_id', Auth::user()->id)->get() as $repo) {
            $repo->group_id = Auth::user()->group->id;
            $repo->save();
        }

        return redirect()->action('HomeController@dashboard');
    }

    public function manage($server)
    {
        $server = Server::where('id', $server)
            ->with(
                [
                    'deployments' => function ($query) {
                        $query->orderBy('created_at', 'desc');
                    }
                ]
            )->first();
        if (!$server
            || !Auth::user()->group->environments->contains($server->environment)) {
            return redirect()->action("HomeController@dashboard")
                ->with("error", "The specified server couldn't be found.");
        }

        return view('server.manage')->with(compact('server'));
    }

    public function invite()
    {
        if (Auth::user()->group->plan->user_limit > 0
            && Auth::user()->group->users->count() >= Auth::user()->group
            ->plan->user_limit) {
            return redirect()->action("HomeController@dashboard")
                ->with(
                    "error",
                    "Your group is currently at the user limit. 
                    Before you can continue you must remove users or upgrade your 
                    plan."
                );
        }

        if (!Auth::user()->can('manage-users')) {
            return redirect()->action('HomeController@dashboard')
                ->with('error', "You don't have permission to invite users.");
        }

        return view('group.invite');
    }

    public function sendInvite(Request $request)
    {
        $this->validate(
            $request,
            [
                'email' => 'required|email',
            ]
        );

        dispatch(new SendRegistrationInvite($request->email, Auth::user()->group));

        return redirect()->action('HomeController@dashboard')
            ->with('message', "Your invite will be sent shortly.");
    }
}
