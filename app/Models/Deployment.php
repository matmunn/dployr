<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deployment extends Model
{
    //

    public function server()
    {
        return $this->belongsTo('App\Models\Server');
    }
}
