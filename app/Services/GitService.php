<?php

namespace App\Services;

use Storage;
use App\Http\Requests;
use App\Models\Repository;
use GitWrapper\GitWrapper;
use Illuminate\Http\Request;

class GitService
{
    protected $repository;

    /**
     * Construct an instance of our git handler
     *
     * @param App\Models\Repository $repository An instance of our repository
     * @return void
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
        chmod($this->repository->privateKeyPath(), 0600);
    }

    /**
     * Clean up when we're done with our handler
     *
     * @return void
     */
    public function __destruct()
    {
        chmod($this->repository->privateKeyPath(), 0777);
    }

    /**
     * Get a git instance instantiated in our repository's root folder
     *
     * @return GitWrapper\GitWrapper
     */
    public function getGitInstance($includeCopy = true)
    {
        $wrapper = new GitWrapper(env('GIT_BINARY', '/usr/bin/git'));
        $wrapper->setPrivateKey($this->repository->privateKeyPath());
        $wrapper->setTimeout(env('GIT_TIMEOUT', 600));
        // dd($wrapper);
        if ($includeCopy) {
            $wrapper = $wrapper->workingCopy($this->repository->repositoryPath);
        }
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
        if (is_null($commit2)) {
            $commit2 = $commit1 . "~1";
        }
        $git = $this->getGitInstance();
        $output = $git->run(['diff', '--name-status', $commit2, $commit1]);

        return $output;
    }

    /**
     * Get the SHA1 hash of the current commit
     *
     * @return string SHA1 hash of current string
     */
    public function currentCommit()
    {
        $git = $this->getGitInstance();
        $output = preg_replace('/\n/', '', $git->run(['rev-parse', 'HEAD']));

        return $output;
    }

    /**
     * Get commit message of git commit
     *
     * @param string SHA1 hash of commit
     * @return string
     */
    public function getCommitMessage($commit)
    {
        $git = $this->getGitInstance();
        $output = $git->getWrapper()->git(
            'log --format=%B -n 1 '. $commit,
            $git->getDirectory()
        );

        return $output;
    }

    /**
     * Get the current branch of the repository
     *
     * @return string Current branch name
     */
    public function getCurrentBranch()
    {
        $git = $this->getGitInstance();
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
        if ($newBranch == $this->getCurrentBranch()
            || !in_array($newBranch, $this->getBranches('remote'))
        ) {
            return false;
        }

        $git = $this->getGitInstance();

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
        $git = $this->getGitInstance();

        if ($type == 'remote') {
            $branches = $git->getBranches()->remote();
        } else {
            $branches = $git->getBranches()->all();
            $branches = array_filter(
                $branches,
                function ($val) {
                    return !preg_match('/^remotes/', $val);
                }
            );
        }

        for ($i = 0; $i < count($branches); $i++) {
            $branch = preg_replace("/(\* |\w+\/)(\w+)( .+)?/", "$2", trim($branches[$i]));
            if ($branch !== "HEAD") {
                $branches[$i] = $branch;
            } else {
                $branches[$i] ="";
            }
        }

        $branches = array_filter($branches);

        return $branches;
    }

    /**
     * Get the underlying repository
     *
     * @return App\Models\Repository The underlying repository
     */
    public function getRepository()
    {
        return $this->repository;
    }
}
