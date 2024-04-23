<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Avatar;
use App\Events\GameStart;

use Illuminate\Http\Request;

class GameController extends Controller
{
    public function getUserData()
    {
        $user = Auth::user();

        $nickname = $user->name;
        $avatar = "/storage/avatars/" . Avatar::findOrFail($user->avatar_id)->image;
        $uuid = $user->id;

        $userData = [
            'nickname' => $nickname,
            'avatar' => $avatar,
            'uuid' => $uuid
        ];

        return $userData;
    }

    public function startGame(Request $request)
    {
        $roomCode = $request->roomCode;
        $settings = $request->settings;

        // Lógica para guardar la configuración de la partida

        // Notificar a todos los jugadores en la sala
        broadcast(new GameStart($roomCode));

        return response()->json(['mensaje' => 'Partida iniciada']);
    }
}
