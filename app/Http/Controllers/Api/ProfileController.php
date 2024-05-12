<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function update(Request $request)
    {
        $profile = Auth::user();
        $profile->nickname = $request->nickname;
        $profile->email = $request->email;

        $profile->save();
    }

    public function user(Request $request)
    {
        $user = $request->user();

        return $this->successResponse($user, 'User found');
    }
}
