<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotifierSlack extends Model
{
    //
    protected $table = 'slack_notifiers';

    protected $fillable = [
        'endpoint',
    ];
}
