<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Avatar;


class AvatarController extends Controller
{
    // Obtiene el avatar por su id
    public function getAvatar($id)
    {
        return Avatar::findOrFail($id);
    }
}
