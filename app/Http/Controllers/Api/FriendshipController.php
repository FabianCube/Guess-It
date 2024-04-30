<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FriendRequest;
use App\Models\Friends;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Events\FriendRequestSent;

class FriendshipController extends Controller
{
    // Crea la relación de amistad entre dos usuarios y elimina la solicitud
    public function accept(Request $request, $requestId)
    {
        $friendRequest = FriendRequest::findOrFail($requestId);
        Friends::create([
            'user_id_1' => $friendRequest->user_id_1,
            'user_id_2' => $friendRequest->user_id_2,
        ]);
        $friendRequest->delete();

        return response()->json(['message' => 'Friend request accepted!']);
    }

    // Elimina la solicitud de amistad después de denegarla
    public function reject(Request $request, $requestId)
    {
        $friendRequest = FriendRequest::findOrFail($requestId);
        $friendRequest->delete();

        return response()->json(['message' => 'Friend request rejected!']);
    }

    // Genera una solicitud de amistad
    public function sendRequest(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $recipient = User::where('email', $request->email)->firstOrFail();
        $sender_id = auth()->id(); // Asegura que el usuario esté autenticado

        // Verificar si ya son amigos
        $existingFriendship = Friends::where(function ($query) use ($sender_id, $recipient) {
            $query->where('user_id_1', $sender_id)
                ->where('user_id_2', $recipient->id);
        })->orWhere(function ($query) use ($sender_id, $recipient) {
            $query->where('user_id_1', $recipient->id)
                ->where('user_id_2', $sender_id);
        })->exists();

        if ($existingFriendship) {
            return response()->json(['message' => 'Ya es tu amigo'], 400);
        }

        // Evitar enviar una solicitud a uno mismo
        if ($recipient->id === $sender_id) {
            return response()->json(['message' => 'No puedes enviarte una solicitud a ti mismo'], 400);
        }

        // Verificar si ya existe la solicitud
        $existingRequest = FriendRequest::where(function ($query) use ($sender_id, $recipient) {
            $query->where('user_id_1', $sender_id)
                ->where('user_id_2', $recipient->id);
        })->orWhere(function ($query) use ($sender_id, $recipient) {
            $query->where('user_id_1', $recipient->id)
                ->where('user_id_2', $sender_id);
        })->exists();

        if ($existingRequest) {
            return response()->json(['message' => 'Ya existe una solicitud de amistad pendiente'], 400);
        }

        // Crear solicitud de amistad
        FriendRequest::create([
            'user_id_1' => $sender_id,
            'user_id_2' => $recipient->id,
        ]);

        event(new FriendRequestSent($recipient->id));

        return response()->json(['message' => 'Solicitud de amistad enviada correctamente']);
    }

    // Devuelve las solicitudes de amistad que tiene un usuario
    public function getFriendRequests()
    {
        $userId = auth()->id();
        $friendRequests = FriendRequest::where('user_id_2', $userId)->with('sender')->get();

        return response()->json($friendRequests);
    }

    // Obtiene la lista de amigos del usuario
    public function listFriends()
    {
        $userId = auth()->id();
        $friends = Friends::where('user_id_1', $userId)
            ->orWhere('user_id_2', $userId)
            ->with('user1', 'user2')
            ->get()
            ->map(function ($friend) use ($userId) {
                return $friend->user_id_1 == $userId ? $friend->user2 : $friend->user1;
            });

        return response()->json($friends);
    }
}
