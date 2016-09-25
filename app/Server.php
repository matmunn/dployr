<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Server extends Model
{
    //
    use SoftDeletes;

    public function setServerNameAttribute($value)
    {
        return Crypt::encrypt($value);
    }

    public function getServerNameAttribute()
    {
        return Crypt::decrypt($this->serverName);
    }

    public function environment()
    {
        return $this->belongsTo('App\Models\Environment');
    }
}
