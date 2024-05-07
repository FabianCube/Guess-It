<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EncryptedWord implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $code;
    public $encryptedWord;

    /**
     * Create a new event instance.
     */
    public function __construct($code, $encryptedWord)
    {
        $this->code = $code;
        $this->encryptedWord = $encryptedWord;
    }

    public function broadcastOn()
    {
        return new Channel('room-'.$this->code);
    }

    public function broadcastAs()
    {
        return 'EncryptedWord';
    }
}
