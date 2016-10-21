<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deployment extends Model
{
    //
    protected $fillable = [
        'started_at',
        'finished_at',
        'commit_hash',
        'commit_message'
    ];

    public function server()
    {
        return $this->belongsTo('App\Models\Server');
    }
}
