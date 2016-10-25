<?php

namespace App\Models;

use Laravel\Cashier\Billable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Billable;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

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
}
