<?php

namespace App\Http\Controllers\Api;

use App\Events\CanvasUpdate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;

class ChatsController extends Controller
{
    public function fetchMessages() //! NOT USED?
    {
        return Message::with('user')->get();
    }

    public function sendMessage(Request $request)
    {
        // $user = Auth::user();
        // $user = $request->user;
        // $message = $user->messages()->create([
        //     'message' => $request->input('message')
        // ]);

        // broadcast(new MessageSent($user, $message))->toOthers();

        // return ['status' => 'Message Sent!'];


        $userData = $request->input('user');
        $messageText = $request->input('message');

        if (!$userData) {
            return response()->json(['error' => 'Datos de usuario no proporcionados'], 400);
        }

        $message = new Message([
            'nickname' => $userData['nickname'],
            'message' => $messageText
        ]);

        // Broadcast del evento
        broadcast(new MessageSent($message))->toOthers();

        return ['status' => 'Message Sent!'];
    }

    public function fetchCanvas()
    {
        return "";
    }

    public function sendCanvas(Request $request)
    {
        $user = Auth::user();
        $canvas = $request->input('canvas');

        broadcast(new CanvasUpdate($user, $canvas))->toOthers();
        // broadcast(new CanvasUpdate($canvas))->toOthers();

        return ['status' => 'Canvas Sent!'];
    }
}
