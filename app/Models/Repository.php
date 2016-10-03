<?php

namespace App\Models;

use GitWrapper\GitWrapper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Repository extends Model
{
    /**
     *
     * Repository Statuses
     * 
     * 1 = Idle
     * 2 = Initialising
     * 4 = Updating
     * 8 = Deploying
     * 16 = Error
     *
     */
    protected $fillable = [
        'name',
        'url',
    ];

    public function generateSecretKey()
    {
        $this->secret_key = hash("sha256", $this->user->name . $this->name . microtime());
        $this->save();
    }

    public function getGitInstance()
    {
        $wrapper = new GitWrapper(env('GIT_BINARY', '/usr/bin/git'));
        $wrapper->setPrivateKey($this->privateKeyPath());
        // dd($wrapper);
        return $wrapper;
    }

    public function changedFiles($commit1 = "HEAD", $commit2 = null)
    {
        if(!isset($commit2) || is_null($commit2))
        {
            $commit2 = $commit1 . "~1";
        }
        $git = $this->getGitInstance()->workingCopy($this->repositoryPath);
        $output = $git->run(['diff', '--name-status', $commit1, $commit2]);

        return $output;
    }

    public function currentCommit()
    {
        $git = $this->getGitInstance()->workingCopy($this->repositoryPath);
        $output = $git->run(['rev-parse', 'HEAD']);

        return $output;
    }

    public function privateKeyPath($absolute = true)
    {
        $path = 'keys/repos/'.$this->id;
        if($absolute)
        {
            return storage_path('app/'.$path);
        }

        return $path;
    }

    public function getCurrentBranch()
    {
        $git = $this->getGitInstance()->workingCopy($this->repositoryPath);
        return $git->getBranches()->head();
    }

    public function changeBranch($newBranch)
    {
        if($newBranch == $this->getCurrentBranch() || !in_array($newBranch, $this->getBranches('remote')))
        {
            return false;
        }

        $git = $this->getGitInstance()->workingCopy($this->repositoryPath);

        $git->checkout($newBranch);
        $git->pull('origin', $newBranch);
        return true;
    }

    public function getBranches($type = null)
    {
        $git = $this->getGitInstance()->workingCopy($this->repositoryPath);

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

    public function getRepositoryPathAttribute()
    {
        return storage_path('app/repos/'.$this->id);
    }


    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function environments()
    {
        return $this->hasMany('App\Models\Environment');
    }

    public function deployments()
    {
        return $this->hasMany('App\Models\Deployments');
    }
}