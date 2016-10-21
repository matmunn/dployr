<?php

namespace App\Http\Controllers;

use Storage;
use App\Http\Requests;
use phpseclib\Crypt\RSA;
use App\Models\Repository;
use App\Services\GitService;
use Illuminate\Http\Request;
use App\Jobs\CloneRepository;
use App\Jobs\DeleteRepository;
use App\Jobs\UpdateRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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
            return redirect()->action('RepositoryController@list')->with('error', "The specified repository couldn't be found.");
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
            return redirect()->action('RepositoryController@new')->withInput()->with('error', 'You have already connected that repository.');
        }

        if(Auth::user()->plan->repository_limit > 0 && Auth::user()->repositories->count() == Auth::user()->plan->repository_limit)
        {
            return redirect()->action('RepositoryController@list')->with("error", "You are at your repository limit, please disconnect a repository before trying to connect another one.");
        }

        $repo = new Repository(['name' => $request->name, 'url' => $request->url]);

        $rsa = new RSA();
        $rsa->setPublicKeyFormat(RSA::PUBLIC_FORMAT_OPENSSH);
        $keys = $rsa->createKey();
        $pubKey = $keys['publickey'];
        $pubKey = str_replace('phpseclib-generated-key', str_replace(" ", "", $repo->name).'@Dployr', $pubKey);
        $repo->public_key = $pubKey;
        Auth::user()->repositories()->save($repo);
        $repo->save();
        $repo->generateSecretKey();

        Storage::put($repo->privateKeyPath(false), $keys['privatekey']);
        chmod($repo->privateKeyPath(), 0777);

        $repo->status = $repo::STATUS_INITIALISING;
        $repo->last_action = "clone";
        $repo->save();

        dispatch(new CloneRepository(new GitService($repo)));

        return redirect()->action('RepositoryController@list')->with("message", "Your repository has been queued for initialisation.");
    }

    public function initialise($repo)
    {
        if(!$repo = Auth::user()->repositories->find($repo))
        {
            return redirect()->action('RepositoryController@list')->with('error', "Couldn't find the specified repository.");
        }

        if($repo->status == $repo::STATUS_IDLE)
        {
            return redirect()->action('RepositoryController@manage', $repo)->with('message', "Your repository is already initialised.");
        }

        $repo->last_action = "initialise";
        $repo->save();

        dispatch(new CloneRepository(new GitService($repo)));

        return redirect()->action('RepositoryController@manage', $repo)->with('message', "Your repository has been queued for initialisation.");
    }

    public function key($repo)
    {
        if(!$repo = Auth::user()->repositories->find($repo))
        {
            return redirect()->action('RepositoryController@list')->with('error', "Couldn't find the specified repository.");
        }

        return response($repo->public_key)->header("Content-Type", "text/plain")->header('Content-disposition', 'attachment; filename="'. str_replace(' ', '_', $repo->name) .'_key.txt"');
    }

    public function delete($repo)
    {
        if(!$repo = Auth::user()->repositories->find($repo))
        {
            return respose()->json("false", 403);
        }

        dispatch(new DeleteRepository($repo));

        session()->flash('message', "Repository successfully queued for deletion.");
        return response()->json("true", 200);
    }
}
