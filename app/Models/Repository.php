<?php

namespace App\Models;

use phpseclib\Crypt\RSA;
use GitWrapper\GitWrapper;
use App\Services\GitService;
use App\Jobs\CloneRepository;
use Illuminate\Support\Facades\Auth;
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
        $this->secret_key = hash("sha256", $this->group->name . $this->name . microtime());
        $this->save();
    }

    /**
     * Generate keys for the repository and get initialisation started
     *
     * @return void
     */
    public function prepInitialisation()
    {
        $rsa = new RSA();
        $rsa->setPublicKeyFormat(RSA::PUBLIC_FORMAT_OPENSSH);
        $keys = $rsa->createKey();
        $pubKey = $keys['publickey'];
        $pubKey = str_replace(
            'phpseclib-generated-key',
            camel_case($this->name).'@Dployr',
            $pubKey
        );
        $this->public_key = $pubKey;
        Auth::user()->group->repositories()->save($this);
        $this->save();
        $this->generateSecretKey();

        Storage::put($this->privateKeyPath(false), $keys['privatekey']);
        chmod($this->privateKeyPath(), 0777);

        dispatch(new CloneRepository(new GitService($this)));
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

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function environments()
    {
        return $this->hasMany(Environment::class);
    }
}
