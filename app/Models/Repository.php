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

    /*
     * Repository statuses
     *
     */
    const STATUS_IDLE = 1;
    const STATUS_INITIALISING = 2;
    const STATUS_UPDATING = 4;
    const STATUS_DEPLOYING = 8;
    const STATUS_ERROR = 16;

    protected $fillable = [
        'name',
        'url',
    ];

    public function generateSecretKey()
    {
        $this->secret_key = hash("sha256", $this->user->name . $this->name . microtime());
        $this->save();
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