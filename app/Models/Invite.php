<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    //
    protected $fillable = [
        'hash',
        'email',
    ];

    protected $dates = [
        'expires_at',
    ];

    /**
     * Generate a secret key for the repository
     *
     * @return void
     */
    public function generateSecretKey()
    {
        $this->hash = hash("sha256", $this->email . microtime());
    }

    public function group()
    {
        return $this->belongsTo(\App\Models\Group::class);
    }
}
