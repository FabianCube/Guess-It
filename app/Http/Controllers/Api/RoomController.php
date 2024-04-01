<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class RoomController extends Controller
{
    // Método para crear una sala
    public function createRoom()
    {
        $code = Str::random(6); // Genera un código único para la sala

        // Inicializa la sala con un array de jugadores vacío
        $room = ['players' => []];
        Cache::put('room_' . $code, $room, now()->addMinutes(120));

        return response()->json(['code' => $code]);
    }

    // Método para unirse a una sala existente
    public function enterRoom(Request $request)
    {
        $code = $request->code;
        $playerName = $request->nombre;

        $room = Cache::get('room_'.$code);
        if (!$room) {
            return response()->json(['mensaje' => 'Sala no encontrada'], 404);
        }

        $room['players'][] = ['nickname' => $playerName];
        Cache::put('room_'.$code, $room, now()->addMinutes(120));

        return response()->json(['mensaje' => 'Jugador añadido a la sala']);
    }
}
