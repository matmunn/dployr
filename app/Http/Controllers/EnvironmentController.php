<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnvironmentController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function manage($environment)
    {
        if(!$env = Auth::user()->environments->find($environment))
        {
            dd("Couldn't find the environment for the logged in user.");
        }

        return view('environment.manage')->with(compact('env'));
    }

    public function new($repo)
    {
        if(!$repo = Auth::user()->repositories->find($repo))
        {
            return redirect()->action('HomeController@dashboard');
        }

        return view('environment.new')->with(compact('repo'));
    }

    public function save(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'type' => 'required',
            'branch' => 'required',
        ]);

        if(!$repo = Auth::user()->repositories->find($request->repo))
        {
            dd("No repository could be found for your account.");
        }

        if($repo->environments->where('branch', $request->branch)->first())
        {
            dd("There is already an environment tracking this branch for this repository.");
        }

        $env = $repo->environments()->create([
            'name' => $request->name,
            'branch' => $request->branch,
        ]);

        // return view('server.new.'.$request->type);
        return redirect()->action('ServerController@new', [$env->id, $request->type]);
    }
}
