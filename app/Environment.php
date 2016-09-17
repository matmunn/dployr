<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Environment extends Model
{
    //
    public function repository()
    {
        return $this->belongsTo('App\Models\Repository');
    }
}
