<?php

namespace App\Models;

use GitWrapper\GitWrapper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Repository extends Model
{
    //
    protected $fillable = [
        'name',
        'url',
    ];

    public function generateSecretKey()
    {
        $this->secret_key = Hash::make($this->user->name . $this->name . microtime());
        $this->save();
    }

    public function getGitInstance()
    {
        $wrapper = new GitWrapper('/usr/bin/git');
        $wrapper->setPrivateKey($this->privateKeyPath);
        return $wrapper;
    }

    public function getPrivateKeyPathAttribute()
    {
        return storage_path('app/keys/repos/'.$this->id);
    }

    public function getRepositoryPathAttribute()
    {
        return storage_path('app/repos/'.$this->id);
    }

    public function getBranchListAttribute()
    {
        $git = $this->getGitInstance()->workingCopy($this->repositoryPath);
        $output = $git->run(['branch', '-r']);

        $branches = $output->getOutput();
        $branches = explode("\n", $branches);
        $branches = array_slice($branches, 1, count($branches) - 2);
        for($i = 0; $i < count($branches); $i++)
        {
            $branches[$i] = str_replace("origin/", "", trim($branches[$i]));
        }

        return $branches;
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function environments()
    {
        return $this->hasMany('App\Models\Environment');
    }
}
