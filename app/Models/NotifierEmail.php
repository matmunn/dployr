<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotifierEmail extends Model
{
    //
    protected $table = 'email_notifiers';

    protected $fillable = [
        'name',
        'email',
    ];

    public function environment()
    {
        return $this->belongsTo('App\Models\Environment');
    }
}
