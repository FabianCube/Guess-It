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
use App\Events\SendWord;
use App\Events\BarStatus;
use App\Events\RoundFinished;
use App\Events\CorrectWord;
use Illuminate\Support\Facades\DB;

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

    // Método para crear las tablas para la partida con la configuración y jugadores
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
        foreach ($players as  $index => $player) {
            $registeredUser = User::getUserById($player['uuid']);

            // Los colores a asignar a cada jugador
            $colors = ['#950398', '#d42424', '#1ed621', '#1f39df', '#fb930c', '#fcf010'];

            $history = new History();
            $history->game_id = $game->id;
            if ($registeredUser) {
                $history->user_id = $player['uuid'];
            } else {
                $history->anonymous_user_id = $player['uuid'];
            }
            $history->user_points = 0;
            $history->user_position = $index;
            $history->save();

            // A cada jugador le asignamos el mismo color que tenía en create-room
            $playerDetails[] = [
                'uuid' => $player['uuid'],
                'nickname' => $player['nickname'],
                'avatar' => $player['avatar'],
                'color' => $colors[$index % count($colors)],
                'position' => $index + 1,
                'points' => 0
            ];
        }

        $gameData = [
            'mensaje' => 'Partida iniciada',
            'game_id' => $game->id,
            'rounds' => $game->rounds,
            'time_per_round' => $game->time_per_round,
            'difficulty' => $game->difficulty,
            'players' => $playerDetails
        ];

        // Notificar a todos los jugadores en la sala
        broadcast(new GameStart($code, $gameData));

        // Devolvemos todos los datos de la partida y sus jugadores
        return response()->json([
            'mensaje' => 'Partida iniciada',
            'game_id' => $game->id,
            'rounds' => $game->rounds,
            'time_per_round' => $game->time_per_round,
            'difficulty' => $game->difficulty,
            'players' => $playerDetails
        ]);
    }

    public function getWord(Request $request)
    {
        $difficulty = $request->difficulty;

        $words = DB::table('words')->where('difficulty', $difficulty)->pluck('word')->toArray();

        Log::info("GameController ===== getWord ===== ", ['words' => $words, 'difficulty' => $difficulty]);
        return response()->json($words);
    }

    public function broadcastWord(Request $request)
    {
        $word = $request->word;
        $code = $request->code;

        broadcast(new SendWord($code, $word))->toOthers();
        Log::info("GameController ==> broadcastWord: ", ['word' => $word]);

        return response()->json([
            'word' => $word
        ]);
    }

    public function broadcastBarStatus(Request $request)
    {

        $code = $request->code;
        $time = $request->time;

        broadcast(new BarStatus($code, $time))->toOthers();

        return response()->json([
            'time' => $time
        ]);
    }

    public function broadcastRoundFinished(Request $request)
    {

        $code = $request->code;
        $finished = $request->finished;

        broadcast(new RoundFinished($code, $finished))->toOthers();

        return response()->json([
            'finished' => $finished
        ]);
    }

    // Calcula la puntuación a sumar al usuario
    public function correctWord(Request $request)
    {
        $code = $request->code;
        $userId = $request->userId;
        $elapsedTime = $request->elapsedTime;
        $guessOrder = $request->guessOrder;

        // Puntos base por ser el primero, segundo, etc.
        $basePoints = [100, 75, 50, 25, 10];

        // Reduce los puntos en un 5% por cada 10 segundos transcurridos
        $timeFactor = 0.05;

        // Calcula los puntos base según el orden de acierto
        $orderPoints = $basePoints[$guessOrder - 1];

        // Reducción de puntos según el tiempo que ha pasado
        $decrement = floor($elapsedTime / 10);

        // Reducción total en porcentaje
        $reduction = $decrement * $timeFactor;

        // Calcular los puntos finales
        $points = round($orderPoints * (1 - $reduction));

        broadcast(new CorrectWord($code, $userId, $points, $guessOrder));

        return response()->json([
            'UserId: ' => $userId
        ]);
    }
}
