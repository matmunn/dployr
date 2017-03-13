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

    public function list()
    {
        $repositories = Auth::user()->group->repositories;

        return view('repository.list')->with(compact('repositories'));
    }

    public function manage($repo)
    {
        if (!$repo = Auth::user()->group->repositories()->where('id', $repo)
            ->with('environments')->first()) {
            return redirect()->action('RepositoryController@list')
                ->with('error', "The specified repository couldn't be found.");
        }
        
        return view('repository.manage')->with(compact('repo'));
    }

    public function new()
    {
        return view('repository.new');
    }

    public function save(Request $request)
    {
        $this->validate(
            $request,
            [
                'name' => 'required|string',
                'url' => 'required|string'
            ]
        );

        if (strstr($request->url, 'https://')) {
            return redirect()->action('RepositoryController@new')->withInput()
                ->with(
                    'error',
                    'HTTPS login is currently unsupported, please use SSH URLs.'
                );
        }

        if ($repo = Auth::user()->group->repositories
            ->where('url', $request->url)->first()) {
            return redirect()->action('RepositoryController@new')->withInput()
                ->with('error', 'You have already connected that repository.');
        }

        if (Auth::user()->group->plan->repository_limit > 0 &&
            Auth::user()->group->repositories->count() ==
            Auth::user()->group->plan->repository_limit) {
            return redirect()->action('RepositoryController@list')
                ->with(
                    "error",
                    "You are at your repository limit, please \
                    disconnect a repository before trying to connect another one."
                );
        }

        $repo = new Repository(['name' => $request->name, 'url' => $request->url]);
        $repo->prepInitialisation();

        return redirect()->action('RepositoryController@list')
            ->with("message", "Your repository has been queued for initialisation.");
    }

    public function initialise($repo)
    {
        if (!$repo = Auth::user()->group->repositories->find($repo)) {
            return redirect()->action('RepositoryController@list')
                ->with('error', "Couldn't find the specified repository.");
        }

        if ($repo->status == $repo::STATUS_INITIALISING) {
            return redirect()->action('RepositoryController@list')
                ->with("error", "Your repository is already initialising.");
        }

        if ($repo->status == $repo::STATUS_IDLE) {
            return redirect()->action('RepositoryController@manage', $repo)
                ->with('message', "Your repository is already initialised.");
        }

        $repo->last_action = "initialise";
        $repo->save();

        dispatch(new CloneRepository(new GitService($repo)));

        return redirect()->action('RepositoryController@manage', $repo)
            ->with('message', "Your repository has been queued for initialisation.");
    }

    public function key($repo)
    {
        if (!$repo = Auth::user()->group->repositories->find($repo)) {
            return redirect()->action('RepositoryController@list')
                ->with('error', "Couldn't find the specified repository.");
        }

        return response($repo->public_key)
            ->header("Content-Type", "text/plain")
            ->header(
                'Content-disposition',
                'attachment; filename="' . str_replace(' ', '_', $repo->name) .
                '_key.txt"'
            );
    }

    public function delete($repo)
    {
        if (!$repo = Auth::user()->group->repositories->find($repo)) {
            return response()->json("false", 403);
        }

        if ($repo->status == $repo::STATUS_INITIALISING) {
            session()->flash('error', "Your repository is still initialising.");
            return;
        }

        if (!Auth::user()->can('disconnect-repository')) {
            session()->flash(
                'error',
                "You don't have permission to disconnect repositories."
            );
            return;
        }

        dispatch(new DeleteRepository($repo));

        session()->flash('message', "Repository successfully queued for deletion.");
        return response()->json("true", 200);
    }
}
