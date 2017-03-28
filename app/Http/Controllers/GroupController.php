<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Group;
use App\Http\Requests;
use App\Models\Server;
use App\Models\Repository;
use App\Models\Environment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendRegistrationInvite;
use Illuminate\Support\Facades\Auth;
use App\Notifications\RemovedFromGroup;

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

    public function removeUser(Request $request)
    {
        if (Auth::user()->id == $request->user) {
            session()->flash("error", "You can't remove yourself.");
            return;
        }

        if (!$user = Auth::user()->group->users
                ->where('id', $request->user)->first()) {
            session()->flash("error", "The given user couldn't be found.");
            return;
        }

        $user->notify(new RemovedFromGroup($user));

        $user->group()->dissociate();
        $user->roles()->sync([]);
        $user->save();

        session()->flash("message", "The user has been removed from your group.");
        return;
    }
}
