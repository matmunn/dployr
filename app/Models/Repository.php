<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Repository extends Model
{
    //
    protected $fillable = [
        'name',
        'url',
    ];

    public function generateSecretKey()
    {
        $this->secret_key = Hash::make($this->user->name . $this->name . microtime());
        $this->save();
    }

    public function getPrivateKeyPathAttribute()
    {
        return storage_path('app/keys/repos/'.$this->id);
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function environments()
    {
        return $this->hasMany('App\Models\Environment');
    }
}
