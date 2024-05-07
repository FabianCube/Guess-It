<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StartTimer implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $code;

    /**
     * Create a new event instance.
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    public function broadcastOn()
    {
        return new Channel('room-'.$this->code);
    }

    public function broadcastAs()
    {
        return 'StartTimer';
    }
}
