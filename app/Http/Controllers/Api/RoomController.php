<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Avatar;
use App\Events\RoomUpdate;

class RoomController extends Controller
{
    // Método para crear una sala
    public function createRoom($userId)
    {
        // Genera un código único para la sala
        $code = strtoupper(Str::random(6));

        // Inicializa la sala con un array de jugadores vacío
        $room = [
            'owner' => $userId,
            'players' => []
        ];
        Cache::put('room_' . $code, $room, now()->addMinutes(120));

        return response()->json(['code' => $code]);
    }

    // Método para unirse a una sala existente
    public function enterRoom(Request $request)
    {
        $code = $request->code;

        if (Auth::check()) {
            $user = Auth::user();
            $nickname = $user->name;

            // Obtenemos el nombre del archivo del avatar a través de su id
            $avatar = "/storage/avatars/" . Avatar::findOrFail($user->avatar_id)->image;
            $uuid = $user->id;
        } else {
            $nickname = $request->nickname;
            $avatar = $request->avatar;
            $uuid = $request->uuid;
        }

        // Si el código proporcionado no coincide con ninguna sala se muestra mensaje de error
        $room = Cache::get('room_' . $code);
        if (!$room) {
            return response()->json(['mensaje' => 'Sala no encontrada'], 404);
        }

        if (count($room['players']) < 6) {
            // Insertamos al jugador en la caché de la sala
            $room['players'][] = ['nickname' => $nickname, 'avatar' => $avatar, 'uuid' => $uuid];
            Cache::put('room_' . $code, $room, now()->addMinutes(120));

            // Dispara el evento con los datos de la sala
            broadcast(new RoomUpdate("New user added"))->toOthers();

            return response()->json(['mensaje' => 'Jugador añadido a la sala']);
        } else {
            return response()->json(['mensaje' => 'Sala completa']);
        }
    }

    // Método para comprobar con el código si una sala existe
    public function findRoom($code)
    {

        $room = Cache::get('room_' . $code);
        if (!$room) {
            return response()->json(['mensaje' => 'Sala no encontrada'], 404);
        }

        if (count($room['players']) < 6) {
            // Si se encuentra la sala devolvemos true
            return response()->json(['mensaje' => true]);
        } else {
            return response()->json(['mensaje' => false]);
        }
    }

    // Método para obtener los jugadores que hay en la sala
    public function getPlayers($code)
    {
        // Si el código proporcionado no coincide con ninguna sala se muestra mensaje de error
        $room = Cache::get('room_' . $code);
        if (!$room) {
            return response()->json(['mensaje' => 'Sala no encontrada'], 404);
        }

        return response()->json($room['players']);
    }

    // Método para obtener el id del jugador que ha creado la partida
    public function getOwner($code)
    {
        // Si el código proporcionado no coincide con ninguna sala se muestra mensaje de error
        $room = Cache::get('room_' . $code);
        if (!$room) {
            return response()->json(['mensaje' => 'Sala no encontrada'], 404);
        }

        return response()->json($room['owner']);
    }

    // Método para sacar a un jugador del array de la sala cuándo sale de la sala
    public function leaveRoom(Request $request, $code)
    {

        $room = Cache::get('room_' . $code);

        // Si el usuario que sale estaba logueado, sacamos los datos de su usuario, 
        // sino, los sacamos del uuid de la sesión
        if (Auth::check()) {
            $user = Auth::user();

            $uuid = $user->id;
        } else {

            Log::info('UUID recibido', ['uuid' => $request->input('uuid')]);
            $uuid = $request->input('uuid');
        }

        if (!$room) {
            return response()->json(['mensaje' => 'Sala no encontrada'], 404);
        }

        // Si el usuario que sale es el creador cerramos la sala
        if ($room['owner'] == $uuid) {
            // Elimina la caché de la sala
            Cache::forget('room_' . $code);

            // Dispara el evento con el mensaje de que un usuario ha salido
            broadcast(new RoomUpdate("User exits room"))->toOthers();

            return response()->json(['mensaje' => 'Sala cerrada y caché eliminada'], 200);
        } else {
            // Filtramos el jugador que está saliendo de la sala
            $room['players'] = array_filter($room['players'], function ($player) use ($uuid) {
                return $player['uuid'] !== $uuid;
            });

            // Actualizamos la caché con el nuevo array de jugadores
            Cache::put('room_' . $code, $room, now()->addMinutes(120));

            // Dispara el evento con los datos de la sala
            broadcast(new RoomUpdate("User exits room"))->toOthers();

            return response()->json(['mensaje' => 'Jugador eliminado de la sala'], 200);
        }
    }
}
