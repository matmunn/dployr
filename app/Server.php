<?php

namespace App\Models;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Model;
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
