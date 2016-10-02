<?php

namespace App\Models;

use Touki\FTP\FTPFactory;
use Touki\FTP\FTPWrapper;
use Touki\FTP\Connection\Connection;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Model;
use Touki\FTP\Exception\ConnectionException;
use Illuminate\Database\Eloquent\SoftDeletes;

class Server extends Model
{
    //
    use SoftDeletes;

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

    public function returnFtpConnection()
    {
        $connection = new Connection($this->server_name, $this->server_username, $this->server_password, $this->server_port, $this->server_timeout, $this->server_passive);
        try
        {
            $connection->open();
        }
        catch(ConnectionException $e)
        {
            return false;
        }

        $factory = new FTPFactory;

        return [$factory, $connection];
    }

    public function returnConnection()
    {
        if($this->type == "ftp")
        {
            $connection = $this->returnFtpConnection();
        }

        if($connection)
        {
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
        return $this->belongsTo('App\Models\Environment');
    }
}
