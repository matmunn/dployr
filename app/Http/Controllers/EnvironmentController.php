<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnvironmentController extends Controller
{
    //

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
        dd($request);
    }
}
