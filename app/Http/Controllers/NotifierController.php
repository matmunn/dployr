<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Notifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifierController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function new($environment, $type)
    {
        if(!$env = Auth::user()->environments->find($environment))
        {
            return redirect()->action('RepositoryController@list')->with('error', "Couldn't find the specified environment.");
        }

        return view('notifier.new.'.$type)->with(compact('env'));
    }

    public function save(Request $request)
    {
        if($request->type == "slack")
        {
            $this->validate($request, [
                'type' => 'required|string',
                'environment' => 'required',
                'endpoint' => 'required|string',
            ]);

            $notifier = ['type' => 'slack', 'data1' => $request->endpoint];
        }
        if($request->type == "email")
        {
            $this->validate($request, [
                'type' => 'required|string',
                'environment' => 'required',
                'address' => 'required|email',
            ]);

            $notifier = ['type' => 'email', 'data1' => $request->address];
        }
        if($request->type == "sms")
        {
            $this->validate($request, [
                'type' => 'required|string',
                'environment' => 'required',
                'phone' => 'required|string',
            ]);

            $notifier = ['type' => 'sms', 'data1' => $request->phone];
        }

        if(!$environment = Auth::user()->environments->find($request->environment))
        {
            return redirect()->action('HomeController@dashboard')->with("error", "The specified environment couldn't be found.");
        }

        $environment->notifiers()->create($notifier);

        return redirect()->action('EnvironmentController@manage', $environment)->with('message', "Your notifier was saved successfully.");
    }
}