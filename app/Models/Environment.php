<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Environment extends Model
{
    //
    protected $fillable = [
        'name',
        'branch',
    ];

    public function repository()
    {
        return $this->belongsTo('App\Models\Repository');
    }

    public function servers()
    {
        return $this->hasMany('App\Models\Server');
    }
}
