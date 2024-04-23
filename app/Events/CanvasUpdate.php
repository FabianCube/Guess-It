<?php

namespace App\Events;

use App\Models\User;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CanvasUpdate implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $canvas;
    public $code;

    /**
     * Create a new event instance.
     */
    public function __construct($user, $canvas, $code)
    {
        $this->code = $code;
        $this->user = $user;
        $this->canvas = $canvas;
        Log::info("Evento canvasUpdate disparado", ['message' => $user, 'user' => $canvas, 'code' => $code]);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return new Channel('room-' . $this->code);
    }

    public function broadcastAs()
    {
        return 'CanvasUpdate';
    }
}
