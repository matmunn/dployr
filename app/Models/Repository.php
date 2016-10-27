<?php

namespace App\Models;

use GitWrapper\GitWrapper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Repository extends Model
{
    /**
     * Repository statuses
     */
    const STATUS_IDLE = 1;
    const STATUS_INITIALISING = 2;
    const STATUS_UPDATING = 4;
    const STATUS_DEPLOYING = 8;
    const STATUS_ERROR = 16;
    const STATUS_DELETING = 32;

    protected $fillable = [
        'name',
        'url',
    ];

    /**
     * Generate a secret key for the repository
     *
     * @return void
     */
    public function generateSecretKey()
    {
        $this->secret_key = hash("sha256", $this->user->name . $this->name . microtime());
        $this->save();
    }

    /**
     * Get the path to the private key for the current repository
     *
     * @param bool $absolute Whether to return absolute or relative path
     * @return string Path to the current repository's private key
     */
    public function privateKeyPath($absolute = true)
    {
        $path = 'keys/repos/'.$this->id;
        if ($absolute) {
            return storage_path('app/'.$path);
        }

        return $path;
    }

    /**
     * Get the storage path for the repository
     *
     * @return string
     */
    public function getRepositoryPathAttribute()
    {
        return storage_path('app/repos/'.$this->id);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function environments()
    {
        return $this->hasMany(\App\Models\Environment::class);
    }
}
