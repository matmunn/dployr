<?php

namespace App\Listeners;

use App\Events\DeploymentComplete;
use App\Notifications\DeploySuccess;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeploymentCompleteListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param DeploymentComplete $event
     * @return void
     */
    public function handle(DeploymentComplete $event)
    {
        //
        $server = $event->server;

        $server->environment->notify(new DeploySuccess($server));
    }
}
