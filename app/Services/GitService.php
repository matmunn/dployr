<?php

namespace App\Services;

use Storage;
use App\Http\Requests;
use App\Models\Repository;
use GitWrapper\GitWrapper;
use Illuminate\Http\Request;

class GitService
{
    protected $repo;

    /**
     * @param App\Models\Repository $repository An instance of our repository
     */ 
    public function __construct(Repository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * Get a git instance instantiated in our repository's root folder
     *
     * @return GitWrapper\GitWrapper
     */
    public function getGitInstance()
    {
        $wrapper = new GitWrapper(env('GIT_BINARY', '/usr/bin/git'));
        $wrapper->setPrivateKey($this->repo->privateKeyPath());
        // dd($wrapper);
        return $wrapper;
    }

    /**
     * Get a list of changed files between two commits
     *
     * @param string $commit1 First commit to get a diff of.
     * @param string|null $commit2 Second commit or null to get a diff of.
     * @return mixed[] List of changed files.
     */
    public function changedFiles($commit1 = "HEAD", $commit2 = null)
    {
        if(!isset($commit2) || is_null($commit2))
        {
            $commit2 = $commit1 . "~1";
        }
        $git = $this->getGitInstance()->workingCopy($this->repo->repositoryPath);
        $output = $git->run(['diff', '--name-status', $commit1, $commit2]);

        return $output;
    }

    /**
     * Get the SHA1 hash of the current commit
     *
     * @return string SHA1 hash of current string
     */
    public function currentCommit()
    {
        $git = $this->getGitInstance()->workingCopy($this->repo->repositoryPath);
        $output = $git->run(['rev-parse', 'HEAD']);

        return $output;
    }

    /**
     * Get the path to the private key for the current repository
     *
     * @param bool $absolute Whether to return absolute or relative path
     * @return string Path to the current repository's private key
     */
    public function privateKeyPath($absolute = true)
    {
        $path = 'keys/repos/'.$this->repo->id;
        if($absolute)
        {
            return storage_path('app/'.$path);
        }

        return $path;
    }

    /**
     * Get the current branch of the repository
     *
     * @return string Current branch name
     */
    public function getCurrentBranch()
    {
        $git = $this->getGitInstance()->workingCopy($this->repo->repositoryPath);
        return $git->getBranches()->head();
    }

    /**
     * Change the current repository's branch
     *
     * @param string $newBranch The branch to change to.
     * @return bool Returns true on success of changing branch
     */
    public function changeBranch($newBranch)
    {
        if($newBranch == $this->getCurrentBranch() || !in_array($newBranch, $this->getBranches('remote')))
        {
            return false;
        }

        $git = $this->getGitInstance()->workingCopy($this->repo->repositoryPath);

        $git->checkout($newBranch);
        $git->pull('origin', $newBranch);
        return true;
    }

    /**
     * Get a list of branches for the current repository
     *
     * @param null|string $type Type of branches to get
     * @return mixed[] List of branches
     */
    public function getBranches($type = null)
    {
        $git = $this->getGitInstance()->workingCopy($this->repo->repositoryPath);

        if($type == 'remote')
        {
            $branches = $git->getBranches()->remote();
        }
        else
        {
            $branches = $git->getBranches()->all();
            $branches = array_filter($branches, function($val)
            {
                return !preg_match('/^remotes/', $val);
            });
        }

        for($i = 0; $i < count($branches); $i++)
        {
            $branch = preg_replace("/(\* |\w+\/)(\w+)( .+)?/", "$2", trim($branches[$i]));
            if($branch !== "HEAD")
            {
                $branches[$i] = $branch;
            }
            else
            {
                $branches[$i] ="";
            }
        }

        $branches = array_filter($branches);

        return $branches;
    }
}