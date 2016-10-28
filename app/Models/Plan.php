<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    //

    protected $casts = [
        'visible' => 'bool',
    ];

    public function groups()
    {
        return $this->hasMany(\App\Models\Group::class);
    }
}
