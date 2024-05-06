<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DrawerPoints implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $code;
    public $points;
    public $userId;

    /**
     * Create a new event instance.
     */
    public function __construct($code, $userId, $points)
    {
        $this->code = $code;
        $this->userId = $userId; 
        $this->points = $points; 
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn()
    {
        return new Channel('room-' . $this->code);
    }

    public function broadcastAs()
    {
        return 'DrawerPoints';
    }
}
