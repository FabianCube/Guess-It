<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Avatar;


class AvatarController extends Controller
{
    public function getAvatar($id)
    {
        return Avatar::findOrFail($id);
    }
}
