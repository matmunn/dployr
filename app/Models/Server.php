<?php

namespace App\Models;

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

    protected $fillable = [
        'name',
        'type',
        'server_name',
        'server_username',
        'server_password',
        'server_path',
    ];

    public function returnFtpConnection()
    {
        $connection = new Connection($this->server_name, $this->server_username, $this->server_password);
        try
        {
            $connection->open();
        }
        catch(ConnectionException $e)
        {
            return false;
        }

        return new FTPWrapper($connection);
    }

    public function returnConnection()
    {
        if($this->type == "ftp")
        {
            $connection = $this->getFtpConnection();
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

    public function environment()
    {
        return $this->belongsTo('App\Models\Environment');
    }
}
