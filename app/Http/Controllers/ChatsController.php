<?php

namespace App\Http\Controllers;

use App\Events\CanvasUpdate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;

class ChatsController extends Controller
{
    //Add the below functions
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('chat');
    }

    public function fetchMessages()
    {
        return Message::with('user')->get();
    }

    public function sendMessage(Request $request)
    {
        $user = Auth::user();
        $message = $user->messages()->create([
            'message' => $request->input('message')
        ]);

        broadcast(new MessageSent($user, $message))->toOthers();

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

        return ['status' => 'Canvas Sent!'];
    }
}
