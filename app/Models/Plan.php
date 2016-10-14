<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    //

    protected $casts = [
        'visible' => 'bool',
    ];

    public function users()
    {
        return $this->hasMany('App\Models\User');
    }
}
