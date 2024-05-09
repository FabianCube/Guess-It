<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeleteCache implements ShouldBroadcast
{
    public $code;

    /**
     * Create a new event instance.
     */
    public function __construct($code)
    {  
        $this->code = $code;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return 
     */
    public function broadcastOn()
    {
        return new Channel('cache');
    }

    public function broadcastAs()
    {
        return 'DeleteCache';
    }
}
