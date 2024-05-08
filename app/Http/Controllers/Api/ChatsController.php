<?php

namespace App\Http\Controllers\Api;

use App\Events\CanvasUpdate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;
use App\Events\CleanCanvas;
use Illuminate\Support\Facades\Log;

class ChatsController extends Controller
{
    public function fetchMessages() //! NOT USED?
    {
        return Message::with('user')->get();
    }

    public function sendMessage(Request $request)
    {

        $code = $request->input('code');
        $userData = $request->input('user');
        $messageText = $request->input('message');

        if (!$userData) {
            return response()->json(['error' => 'Datos de usuario no proporcionados'], 400);
        }

        $message = new Message([
            'user' => $userData,
            'message' => $messageText
        ]);

        // Broadcast del evento
        broadcast(new MessageSent($message, $code))->toOthers();

        Log::info("ChatsController ===== ", ['message' => $message->message, 'user' => $message->user]);
        return ['status' => '[ChatsController.php]:sendMessage:Message Sent!'];
    }

    public function fetchCanvas()
    {
        return "";
    }

    public function sendCanvas(Request $request)
    {
        $code = $request->input('code');
        $user = $request->input('user');
        $canvas = $request->input('canvas');

        broadcast(new CanvasUpdate($user, $canvas, $code))->toOthers();

        Log::info("ChatsController ===== canvas ===== ", ['canvas' => $canvas, 'user' => $user]);
        return ['status' => 'Canvas Sent!'];
    }

    public function cleanCanvas(Request $request)
    {
        $code = $request->input('code');

        broadcast(new CleanCanvas($code))->toOthers();

        return ['status' => 'Canvas clean!'];
    }

}
