<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    //
    protected $fillable = [
        'hash',
    ];

    public function group()
    {
        return $this->belongsTo(\App\Models\Group::class);
    }
}
