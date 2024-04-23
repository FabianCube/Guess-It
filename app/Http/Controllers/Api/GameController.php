<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Avatar;

use Illuminate\Http\Request;

class GameController extends Controller
{
    public function getUserData()
    {
        $user = Auth::user();
        
        $nickname = $user->name;
        $avatar = "/storage/avatars/" . Avatar::findOrFail($user->avatar_id)->image;
        $uuid = $user->id;

        $userData = [
            'nickname' => $nickname, 
            'avatar' => $avatar, 
            'uuid' => $uuid
        ];

        return $userData;
    }

    public function createGame($config){
        
    }
}
