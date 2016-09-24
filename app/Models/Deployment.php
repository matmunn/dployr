<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deployment extends Model
{
    //

    public function repository()
    {
        return $this->belongsTo('App\Models\Repository');
    }
}
