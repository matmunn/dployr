<?php

namespace App\Events;

use App\Models\Repository;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UpdateComplete
{
    use InteractsWithSockets, SerializesModels;

    public $repository;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Repository $repository)
    {
        //
        $this->repository = $repository;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
