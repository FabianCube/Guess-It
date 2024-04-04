<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RoomController extends Controller
{
    // Método para crear una sala
    public function createRoom()
    {
        // Genera un código único para la sala
        $code = strtoupper(Str::random(6)); 

        // Inicializa la sala con un array de jugadores vacío
        $room = ['players' => []];
        Cache::put('room_' . $code, $room, now()->addMinutes(120));

        return response()->json(['code' => $code]);
    }

    // Método para unirse a una sala existente
    public function enterRoom(Request $request)
    {
        $code = $request->code;
        $nickname = $request->nickname;
        $avatar = $request->avatar;
        $uuid = $request->uuid;
        $owner = $request->owner;

        // Si el código proporcionado no coincide con ninguna sala se muestra mensaje de error
        $room = Cache::get('room_' . $code);
        if (!$room) {
            return response()->json(['mensaje' => 'Sala no encontrada'], 404);
        }

        $room['players'][] = ['nickname' => $nickname, 'avatar' => $avatar, 'uuid' => $uuid, 'owner' => $owner];
        Cache::put('room_' . $code, $room, now()->addMinutes(120));

        return response()->json(['mensaje' => 'Jugador añadido a la sala']);
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

    public function isOwner(Request $request, $roomCode){

        $room = Cache::get('room_' . $roomCode);
        // Comprueba si hay un usuario autenticado
        if (Auth::check()) {
            // Usuario autenticado, usa el ID del usuario
            $userId = Auth::id();
        } else {
            // Usuario anónimo, obtén el UUID almacenado en la sesión
            $userId = $request->session()->get('playerUuid', null);
        }

        $owner = ($userId === $room['owner']);

        return response()->json(['owner' => $owner]);
    }
}
