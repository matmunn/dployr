<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Services\GitService;
use GitWrapper\GitException;
use Illuminate\Http\Request;
use App\Jobs\UpdateRepository;
use App\Jobs\DeleteEnvironment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class EnvironmentController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function manage($environment)
    {
        if (!$env = Auth::user()->group->environments->find($environment)) {
            return redirect()->action('HomeController@dashboard')
                ->with('error', "The specified evironment couldn't be found.");
        }

        return view('environment.manage')->with(compact('env'));
    }

    public function new($repo)
    {
        if (!$repo = Auth::user()->group->repositories->find($repo)) {
            return redirect()->action('HomeController@dashboard')
                ->with(
                    'error',
                    "The repository couldn't be found for your account."
                );
        }

        if ($repo->status == $repo::STATUS_INITIALISING) {
            return redirect()->action('RepositoryController@list')
                ->with('error', "Your repository is still initialising.");
        }

        try {
            $git = new GitService($repo);
            $branches = $git->getBranches('remote');
        } catch (GitException $e) {
            return redirect()->action('RepositoryController@manage', $repo)
                ->with('error', "Couldn't get remote branches for your repository.");
        } catch (\ErrorException $e) {
            return redirect()->action('RepositoryController@manage', $repo)
                ->with('error', "There was a problem with your repository.");
        }

        return view('environment.new')->with(compact('repo', 'branches'));
    }

    public function save(Request $request)
    {
        $this->validate(
            $request,
            [
                'name' => 'required|string',
                'type' => 'required',
                'branch' => 'required',
                'deploy_mode' => 'integer'
            ]
        );

        if (!$repo = Auth::user()->group->repositories->find($request->repo)) {
            return redirect()->action('HomeController@dashboard')
                ->with(
                    'error',
                    "The specified repository couldn't be found for your account."
                );
        }

        if (!Auth::user()->can('add-environment')) {
            return redirect()->action('RepositoryController@manage', $repo)
                ->with(
                    'error',
                    "You don't have permission to create a new environment."
                );
        }

        if ($repo->environments->where('branch', $request->branch)->first()) {
            return redirect()->action('RepositoryController@manage', $repo)
                ->with(
                    'error',
                    "There is already an environment tracking this branch ".
                        "for this repository."
                );
        }

        $env = $repo->environments()->create(
            [
                'name' => $request->name,
                'branch' => $request->branch,
            ]
        );

        if ($request->has('deploy_mode')) {
            $env->deploy_mode = $request->deploy_mode;
            $env->save();
        }

        return redirect()->action(
            'ServerController@new',
            [$env->id, $request->type]
        );
    }

    public function deploy($environment)
    {
        if (!$env = Auth::user()->group->environments->find($environment)) {
            return redirect()->action('RepositoryController@list')
                ->with('error', "The specified environment couldn't be found.");
        }

        if (!Auth::user()->can('deploy')) {
            return redirect()->action('EnvironmentController@manage', $env)
                ->with(
                    'error',
                    "You don't have permission to deploy this environment."
                );
        }

        dispatch(new UpdateRepository(new GitService($env->repository), $env->id));

        return redirect()->action('EnvironmentController@manage', $env)
            ->with(
                'message',
                "Your environment was successfully queued for deployment."
            );
    }

    public function delete($environment)
    {
        if (!$env = Auth::user()->group->environments->find($environment)) {
            return respose()->json("false", 403);
        }

        if (!Auth::user()->can('delete-environment')) {
            session()->flash(
                'error',
                "You don't have permission to delete environments."
            );
            return;
        }

        dispatch(new DeleteEnvironment($env));

        session()->flash('message', "Environment successfully queued for deletion.");
        return response()->json("true", 200);
    }
}
