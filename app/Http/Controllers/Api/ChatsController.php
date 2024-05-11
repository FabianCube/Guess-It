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
    // función para enviar un mensaje
    public function sendMessage(Request $request)
    {
        // obtenemos los datos del mensaje que se quiere enviar y los guardamos.
        $code = $request->input('code');
        $userData = $request->input('user');
        $messageText = $request->input('message');

        // si no hay usuario no se puede enviar el mensaje.
        if (!$userData) {
            return response()->json(['error' => 'Datos de usuario no proporcionados'], 400);
        }

        // creamos un nuevo objeto mensaje usando su modelo
        $message = new Message([
            'user' => $userData,
            'message' => $messageText
        ]);

        // Broadcast del evento con el modelo de mensaje.
        broadcast(new MessageSent($message, $code))->toOthers();
        Log::info("ChatsController ===== ", ['message' => $message->message, 'user' => $message->user]);
        
        return ['status' => '[ChatsController.php]:sendMessage:Message Sent!'];
    }

    //! en principio no se usa, pero por si acaso me espero.
    public function fetchCanvas()
    {
        return "";
    }

    // función para enviar los datos del canvas.
    public function sendCanvas(Request $request)
    {
        // obtenemos los datos proporcionados y los guardamos.
        $code = $request->input('code');
        $user = $request->input('user');
        $canvas = $request->input('canvas');

        // hacemos broadcast del evento en toOthers
        broadcast(new CanvasUpdate($user, $canvas, $code))->toOthers();
        Log::info("ChatsController ===== canvas ===== ", ['canvas' => $canvas, 'user' => $user]);

        return ['status' => 'Canvas Sent!'];
    }

    // función para limpiar el canvas
    public function cleanCanvas(Request $request)
    {
        $code = $request->input('code');

        // broadcast del evento en toOthers.
        broadcast(new CleanCanvas($code))->toOthers();

        return ['status' => 'Canvas clean!'];
    }

}
