<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\History;
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
}
