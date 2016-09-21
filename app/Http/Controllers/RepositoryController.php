<?php

namespace App\Http\Controllers;

use Storage;
use App\Http\Requests;
use phpseclib\Crypt\RSA;
use App\Models\Repository;
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

        if($repo = Auth::user()->repositories->where('url', $request->url)->first())
        {
            return redirect()->action('RepositoryController@new')->withInput()->with('error', 'That repository URL already exists');
        }

        $repo = new Repository(['name' => $request->name, 'url' => $request->url]);

        $rsa = new RSA();
        $rsa->setPublicKeyFormat(RSA::PUBLIC_FORMAT_OPENSSH);
        $keys = $rsa->createKey();
        $pubKey = $keys['publickey'];
        $pubKey = str_replace('phpseclib-generated-key', 'Dployr@'.$repo->name, $pubKey);
        $repo->public_key = $pubKey;
        Auth::user()->repositories()->save($repo);
        $repo->generateSecretKey();

        Storage::put('keys/repo/'.$repo->id, $keys['privatekey']);

        dd($repo);
        // $repo->save();
    }
}
