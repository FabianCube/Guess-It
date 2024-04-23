<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Avatar;
use App\Models\User;
use App\Models\Game;
use App\Models\History;
use App\Events\GameStart;

use Illuminate\Http\Request;

class GameController extends Controller
{
    public function getUserData()
    {
        $user = Auth::user();

        $nickname = $user->nickname;
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

        // Obtener jugadores de la caché
        $players = Cache::get('room_' . $code)['players'];

        // Crear registros en la tabla 'history' para cada jugador
        foreach ($players as $player) {
            $registeredUser = User::getUserById($player['uuid']);

            $history = new History();
            $history->game_id = $game->id;
            if($registeredUser){
                $history->user_id = $player['uuid'];
            }else{
                $history->anonymous_user_id = $player['uuid'];
            }
            $history->user_points = 0; // Iniciar puntos a 0
            $history->user_position = 0; // Iniciar posición a 0
            $history->save();
        }

        // Notificar a todos los jugadores en la sala
        broadcast(new GameStart($code));

        return response()->json(['mensaje' => 'Partida iniciada', 'game_id' => $game->id]);
    }
}
