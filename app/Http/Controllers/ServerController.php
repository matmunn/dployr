<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Environment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServerController extends Controller
{
    //
    public function new($environment, $server)
    {
        if(!$repo = Environment::find($environment)->repository)
        {
            dd("Couldn't find the repository for this environment");
        }

        if(!Auth::user()->repositories->contains($repo))
        {
            dd('No matching environment could be found for this user.');
        }

        return view('server.new.'.$server)->with(compact('environment', 'server'));
    }

    public function save(Request $request)
    {
        dd($request);
    }
}
