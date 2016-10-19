<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Environment extends Model
{
    //
    use Notifiable;

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

    public function notifiers()
    {
        return $this->hasMany('App\Models\Notifier');
    }

    public function routeNotificationForMail()
    {
        $recipients = [];
        foreach($this->notifiers->where('type', 'email')->all() as $notify)
        {
            $recipients[] = $notify->data1;
        }

        return $recipients;
    }

    public function routeNotificationForSlack()
    {
        $recipients = [];
        foreach($this->notifiers->where('type', 'slack')->all() as $notify)
        {
            $recipients[] = $notify->data1;
        }

        return $recipients;
    }

    public function routeNotificationForPlivo()
    {
        $recipients = [];
        foreach($this->notifiers->where('type', 'sms')->all() as $notify)
        {
            $recipients[] = $notify->data1;
        }

        return implode("<", $recipients);
    }
    // public function notifierEmail()
    // {
    //     return $this->hasMany('App\Models\NotifierEmail');
    // }
}
