<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RoomUpdate implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $roomData;
    public $code;

    /**
     * Create a new event instance.
     */
    public function __construct($roomData , $code)
    {
        $this->roomData = $roomData;
        $this->code = $code;
        Log::info("Evento RoomUpdate disparado", ['roomData' => $roomData]);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return 
     */
    public function broadcastOn()
    {
        return new Channel('room-' . $this->code);
    }

    public function broadcastAs()
    {
        return 'RoomUpdate';
    }
}
