<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RepositoryController extends Controller
{
    //
    public function list()
    {
        $repositories = Auth::user()->repositories;
        // dd($repositories);
        // dd(count($repositories));

        return view('repository.list')->with(compact('repositories'));
    }

    public function manage($repo)
    {
        if(!$repo = Auth::user()->repositories()->find($repo))
        {
            return "The repo couldn't be found for this user";
        }
        
        return view('repository.manage');
    }

    public function new()
    {
        return view('repository.new');
    }

    public function save(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'url' => 'required|string'
        ]);
        $repo = Auth::user()->repositories()->create(['name' => $request->name, 'url' => $request->url]);
        $repo->generateSecretKey();
        dd($repo->secret_key);
    }
}
