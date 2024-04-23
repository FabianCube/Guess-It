<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Avatar;
use App\Models\Game;
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
        $code = $request->roomCode;
        // Asumiendo que los ajustes vienen directamente en el cuerpo de la petición y no bajo una clave 'settings'
        $settings = $request->settings;

        // Crear un nuevo registro en la tabla 'game'
        $game = new Game();
        $game->rounds = $settings['numberRounds'];
        $game->time_per_round = $settings['roundTime'];
        $game->difficulty = $settings['difficulty'];
        $game->save();

        // Notificar a todos los jugadores en la sala
        broadcast(new GameStart($code));

        return response()->json(['mensaje' => 'Partida iniciada', 'game_id' => $game->id]);
    }
}
