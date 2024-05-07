<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\History;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AccountController extends Controller
{
    public function getUserHistory(Request $request)
    {
        $user = $request->user();
        $userHistory = History::where('user_id', $user->id)->get();

        Log::info("AccountController ===== ", ['history' => $userHistory, 'user_id' => $user->id]);

        return response()->json($userHistory);
    }

    public function getAllUsers()
    {
        $users = User::getAllUsers();

        Log::info("AccountController ===== ", ['users' => $users]);

        return response()->json($users);
    }

    public function getAllGames()
    {
        $games = History::getAllGames();

        Log::info("AccountController ===== ", ['Games: ' => $games]);

        return response()->json($games);
    }

    public function deleteUser(Request $request)
    {
        User::deleteUser($request->id);

        Log::info("AccountController ===== ", ['id to delete ----------> ' => $request->id]);
    }
    
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $user->nickname = $request->input('nickname');
        $user->level = $request->input('level');
        $user->email = $request->input('email');
        $user->avatar_id = $request->input('avatar_id');
        
        $user->save();
        
        Log::info("AccountController ===== ", ['updateUser ----------> ' => $user]);
        return response()->json(['message' => 'update user done']);
    }
}
