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
use App\Events\DrawerPoints;
use App\Events\StartTimer;
use App\Events\EncryptedWord;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class GameController extends Controller
{

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

    // Método para crear las tablas para la partida con la configuración y jugadores
    public function updateGame(Request $request)
    {
        $gameId = $request->game_id; 
        $players = $request->players; 

        // Buscar la partida por ID
        $game = Game::find($gameId);
        if (!$game) {
            return response()->json(['mensaje' => 'Partida no encontrada'], 404);
        }

        // Recorrer cada jugador proporcionado y actualizar su historial
        foreach ($players as $player) {
            $history = History::where('game_id', $gameId)->where(function ($query) use ($player) {
                $query->where('user_id', $player['uuid'])->orWhere('anonymous_user_id', $player['uuid']);
            })->first();

            if ($history) {
                $history->user_points = $player['points'];
                $history->user_position = $player['position'];
                $history->save();
            } else {
                // En caso de que no se encuentre un historial para el jugador, se podría manejar un error o crear un nuevo registro
                return response()->json(['mensaje' => 'Jugador no encontrado en esta partida'], 404);
            }
        }

        // Devolver la confirmación de la actualización
        return response()->json([
            'mensaje' => 'Partida actualizada',
            'game_id' => $game->id,
            'players' => $players
        ]);
    }

    // Obtiene el array de palabras a jugar ssegún la dificultad
    public function getWord(Request $request)
    {
        $difficulty = $request->difficulty;

        $words = DB::table('words')->where('difficulty', $difficulty)->pluck('word')->toArray();

        Log::info("GameController ===== getWord ===== ", ['words' => $words, 'difficulty' => $difficulty]);
        return response()->json($words);
    }

    // Dispara un evento con la palabra a jugar
    public function broadcastWord(Request $request)
    {
        $word = $request->word;
        $code = $request->code;

        broadcast(new SendWord($code, $word))->toOthers();

        return response()->json([
            'word' => $word
        ]);
    }

    // Dispara un evento cuándo termina una ronda
    public function broadcastRoundFinished(Request $request)
    {
        $code = $request->code;
        $finished = $request->finished;

        broadcast(new RoundFinished($code, $finished));

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

        // Reduce los puntos en un 0.05% por cada segundo transcurrido
        $timeFactor = 0.005;

        // Calcula los puntos base según el orden de acierto
        $orderPoints = $basePoints[$guessOrder - 1];

        // Reducción total en porcentaje
        $reduction = $elapsedTime * $timeFactor;

        // Calcular los puntos finales
        $points = round($orderPoints * (1 - $reduction));

        broadcast(new CorrectWord($code, $userId, $points, $guessOrder));

        return response()->json([
            'UserId: ' => $userId
        ]);
    }

    // Calcula la puntuación del usuario que dibuja
    public function drawerPoints(Request $request)
    {
        $code = $request->code;
        $userId = $request->userId;
        $correctPlayers = (int) $request->correctPlayers;
        $totalPlayers = (int) $request->players;

        // Define los puntos base para el jugador que dibuja
        $basePoints = 100;

        // Calcula el porcentaje de jugadores que han adivinado
        $guessRatio = $totalPlayers > 0 ? ($correctPlayers / $totalPlayers) : 0;

        // Se calculan los puntos multiplicando la puntuación base por el porcentaje de jugadores que han acertado
        $points = round($basePoints * $guessRatio);

        broadcast(new DrawerPoints($code, $userId, $points));

        return response()->json([
            'UserId: ' => $userId,
            'points' => $points
        ]);
    }

    // Empieza la cuenta atrás antes de empezar una ronda
    public function startTimer(Request $request)
    {
        $code = $request->code;

        broadcast(new StartTimer($code));

        return response()->json([
            'Room: ' => $code
        ]);
    }

    // Empieza la cuenta atrás antes de empezar una ronda
    public function encryptedWord(Request $request)
    {
        $code = $request->code;
        $encryptedWord = $request->encryptedWord;

        broadcast(new EncryptedWord($code, $encryptedWord))->toOthers();

        return response()->json([
            'EncryptedWord: ' => $encryptedWord
        ]);
    }
}
