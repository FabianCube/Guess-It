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

        // Insertamos al jugador en la caché de la sala
        $room['players'][] = ['nickname' => $nickname, 'avatar' => $avatar, 'uuid' => $uuid];
        Cache::put('room_' . $code, $room, now()->addMinutes(120));

        Log::info('Usuario autenticado', [
            $room
        ]);

        return response()->json(['mensaje' => 'Jugador añadido a la sala']);
    }

    // Método para comprobar con el código si una sala existe
    public function findRoom($code)
    {

        $room = Cache::get('room_' . $code);
        if (!$room) {
            return response()->json(['mensaje' => 'Sala no encontrada'], 404);
        }

        // Si se encuentra la sala devolvemos true
        return response()->json(['mensaje' => true]);
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

    public function getOwner($code)
    {
        // Si el código proporcionado no coincide con ninguna sala se muestra mensaje de error
        $room = Cache::get('room_' . $code);
        if (!$room) {
            return response()->json(['mensaje' => 'Sala no encontrada'], 404);
        }

        return response()->json($room['owner']);
    }
}
