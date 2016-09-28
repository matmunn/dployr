<?php

namespace App\Listeners;

use App\Events\CloneComplete;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CloneCompleteListener
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
     * @param  CloneComplete  $event
     * @return void
     */
    public function handle(CloneComplete $event)
    {
        //
    }
}
