<?php

namespace App\Events;

use App\Models\User;
use App\Models\Message;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Http\Request;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $message;
    public $code;

    /**
     * Create a new event instance.
     */
    public function __construct(Message $message, $code)
    {
        $this->code = $code;
        $this->user = $message->user;
        $this->message = $message->message;
        Log::info("Evento messagesent disparado", ['message' => $message->message, 'user' => $message->user]);
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
        return 'MessageSent';
    }
}
