<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Environment extends Model
{
    //
    protected $fillable = [
        'name',
        'type',
        'branch',
    ];

    public function repository()
    {
        return $this->belongsTo('App\Models\Repository');
    }
}
