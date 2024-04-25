<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GameStart implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $code;
    public $gameData;

    public function __construct($code,$gameData)
    {
        $this->code = $code;
        $this->gameData = $gameData;
    }

    public function broadcastOn()
    {
        return new Channel('room-'.$this->code);
    }

    public function broadcastAs()
    {
        return 'GameStart';
    }
}
