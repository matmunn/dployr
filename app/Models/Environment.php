<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Environment extends Model
{
    //

    /*
     * Deployment Mode Constants
     *
     */
    const DEPLOY_MODE_MANUAL = 1;
    const DEPLOY_MODE_AUTO = 2;

    protected $fillable = [
        'name',
        'branch',
    ];

    public function repository()
    {
        return $this->belongsTo('App\Models\Repository');
    }

    public function servers()
    {
        return $this->hasMany('App\Models\Server');
    }
}
