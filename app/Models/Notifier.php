<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifier extends Model
{
    //
    // protected $table = 'slack_notifiers';

    protected $fillable = [
        'type',
        'data1',
        'data2',
        'data3',
    ];

    public function environment()
    {
        return $this->belongsTo(\App\Models\Environment::class);
    }
}
