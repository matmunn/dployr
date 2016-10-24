<?php

namespace App\Listeners;

use App\Events\UpdateComplete;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateCompleteListener
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
     * @param  UpdateComplete  $event
     * @return void
     */
    public function handle(UpdateComplete $event)
    {
        //
        $repo = $event->repository;
        if ($repo->status == $repo::STATUS_UPDATING &&
            $repo->status != $repo::STATUS_ERROR) {
            $repo->status = $repo::STATUS_IDLE;
            $repo->save();
        }
    }
}
