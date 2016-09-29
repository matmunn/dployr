<?php

namespace App\Http\Controllers;

use Storage;
use App\Http\Requests;
use phpseclib\Crypt\RSA;
use App\Models\Repository;
use GitWrapper\GitWrapper;
use Illuminate\Http\Request;
use App\Jobs\CloneRepository;
use Illuminate\Support\Facades\Auth;

class RepositoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function list()
    {
        $repositories = Auth::user()->repositories;

        return view('repository.list')->with(compact('repositories'));
    }

    public function manage($repo)
    {
        if(!$repo = Auth::user()->repositories()->where('id', $repo)->with('environments')->first())
        {
            return "The repo couldn't be found for this user";
        }
        
        return view('repository.manage')->with(compact('repo'));
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
        $pubKey = str_replace('phpseclib-generated-key', str_replace(" ", "", $repo->name).'@Dployr', $pubKey);
        $repo->public_key = $pubKey;
        Auth::user()->repositories()->save($repo);
        $repo->generateSecretKey();

        Storage::put($repo->privateKeyPath(false), $keys['privatekey']);
        chmod($repo->privateKeyPath(), 0600);

        $repo->status = 2;
        $repo->save();

        dispatch(new CloneRepository($repo));

        return redirect()->action('RepositoryController@list');
    }

    // public function details($repo)
    // {
    //     if(!$repo = Auth::user()->repositories->find($repo))
    //     {
    //         return redirect()->action('HomeController@dashboard');
    //     }
    //     // dd(Storage::url('keys/repo/'.$repo->id));
    //     return view('repository.details')->with(compact('repo'));
    // }

    // public function clone($repo)
    // {
    //     if(!$repo = Auth::user()->repositories->find($repo))
    //     {
    //         return redirect()->action('HomeController@dashboard');
    //     }

    //     Storage::makeDirectory('repos/'.$repo->id);

    //     $git = $repo->getGitInstance()->clone($repo->url, $repo->repositoryPath);
    // }

    // public function branches($repo)
    // {
    //     if(!$repo = Auth::user()->repositories->find($repo))
    //     {
    //         return redirect()->action('HomeController@dashboard');
    //     }

    //     $branches = $repo->getBranches('');

    //     dd($branches);
    // }

    // public function changedFiles($repo)
    // {
    //     if(!$repo = Auth::user()->repositories->find($repo))
    //     {
    //         return redirect()->action('HomeController@dashboard');
    //     }

    //     $branches = $repo->changedFiles();

    //     dd($branches);
    // }

    // public function testing($repo)
    // {
    //     if(!$repo = Auth::user()->repositories->find($repo))
    //     {
    //         return redirect()->action('HomeController@dashboard');
    //     }

    //     $branches = $repo->getCurrentBranch();
    //     var_dump($branches);
    //     $repo->changeBranch('site');
    //     dd($repo->getCurrentBranch());
    // }
}
