<?php

namespace App\Models;

use Laravel\Cashier\Billable;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use Billable;

    //
    protected $fillable = [
        'group_name',
    ];

    public function users()
    {
        return $this->hasMany(\App\Models\User::class);
    }

    public function repositories()
    {
        return $this->hasMany(\App\Models\Repository::class);
    }

    public function environments()
    {
        return $this->hasManyThrough(
            \App\Models\Environment::class,
            \App\Models\Repository::class
        );
    }

    public function plan()
    {
        return $this->belongsTo(\App\Models\Plan::class);
    }

    public function invites()
    {
        return $this->hasMany(\App\Models\Invite::class);
    }
}
