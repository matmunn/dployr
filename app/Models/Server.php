<?php

namespace App\Models;

use UCSDMath\Sftp\Sftp;
use Touki\FTP\FTPFactory;
use Touki\FTP\FTPWrapper;
use Illuminate\Support\Facades\Log;
use Touki\FTP\Connection\Connection;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Model;
use Touki\FTP\Exception\ConnectionException;
use Illuminate\Database\Eloquent\SoftDeletes;

class Server extends Model
{
    /**
     * Repository statuses
     */
    const ERR_CONN_FAILED = 1;

    protected $casts = [
        'server_passive' => 'boolean',
    ];

    protected $fillable = [
        'name',
        'type',
        'server_name',
        'server_username',
        'server_password',
        'server_path',
        'server_port',
        'server_timeout',
        'server_passive',
    ];

    protected function returnSftpConnection()
    {
        $sftp = new Sftp();
        $connectionOptions = [
            'account_host' => $this->server_name,
            'account_username' => $this->server_username,
            'account_password' => $this->server_password,
            'default_directory' => $this->server_path,
        ];
        try {
            $conn = $sftp->connect($connectionOptions);
        } catch (\Exception $e) {
            Log::error($e);
        }

        return $conn;
    }

    protected function returnFtpConnection()
    {
        $connection = new Connection(
            $this->server_name,
            $this->server_username,
            $this->server_password,
            $this->server_port,
            $this->server_timeout,
            $this->server_passive
        );
        try {
            $connection->open();
        } catch (ConnectionException $e) {
            return false;
        }

        $factory = new FTPFactory;

        return [$factory, $connection];
    }

    public function returnConnection()
    {
        if ($this->type == "ftp") {
            $connection = $this->returnFtpConnection();
        }

        if ($this->type == "sftp") {
            $connection = $this->returnSftpConnection();
        }

        if ($connection) {
            return $connection;
        }

        return false;
    }

    public function setServerNameAttribute($value)
    {
        $this->attributes['server_name'] = Crypt::encrypt($value);
    }

    public function getServerNameAttribute($value)
    {
        return Crypt::decrypt($value);
    }

    public function setServerPasswordAttribute($value)
    {
        $this->attributes['server_password'] = Crypt::encrypt($value);
    }

    public function getServerPasswordAttribute($value)
    {
        return Crypt::decrypt($value);
    }

    public function setServerUsernameAttribute($value)
    {
        $this->attributes['server_username'] = Crypt::encrypt($value);
    }

    public function getServerUsernameAttribute($value)
    {
        return Crypt::decrypt($value);
    }

    public function setServerPathAttribute($value)
    {
        $this->attributes['server_path'] = Crypt::encrypt($value);
    }

    public function getServerPathAttribute($value)
    {
        return Crypt::decrypt($value);
    }

    public function environment()
    {
        return $this->belongsTo(\App\Models\Environment::class);
    }

    public function deployments()
    {
        return $this->hasMany(\App\Models\Deployment::class);
    }
}
