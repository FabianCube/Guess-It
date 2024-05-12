<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ExerciseController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AvatarController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\ChatsController;
use App\Http\Controllers\Api\FriendshipController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\GameController;

Route::post('forget-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('forget.password.post');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.reset');

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::apiResource('users', UserController::class);
    Route::apiResource('roles', RoleController::class);

    Route::get('role-list', [RoleController::class, 'getList']);
    Route::get('role-permissions/{id}', [PermissionController::class, 'getRolePermissions']);
    Route::put('/role-permissions', [PermissionController::class, 'updateRolePermissions']);
    Route::apiResource('permissions', PermissionController::class);
    Route::get('/user', [ProfileController::class, 'user']);
    Route::put('/user', [ProfileController::class, 'update']);
    

    Route::get('abilities', function(Request $request) {
        return $request->user()->roles()->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('name')
            ->unique()
            ->values()
            ->toArray();
    });
});


// Avatar controller
Route::get('get-avatar/{id}', [AvatarController::class, 'getAvatar']);

// Room controller
Route::post('create-room/{owner}', [RoomController::class, 'createRoom']);
Route::post('enter-room', [RoomController::class, 'enterRoom']);
Route::get('/room/players/{code}', [RoomController::class, 'getPlayers']);
Route::get('/room/owner/{code}', [RoomController::class, 'getOwner']);
Route::get('find-room/{code}', [RoomController::class, 'findRoom']);
Route::post('leave-room/{code}', [RoomController::class, 'leaveRoom']);
Route::post('invite-user', [RoomController::class, 'inviteUser']);
Route::post('create-cache', [RoomController::class, 'createCache']);
Route::post('delete-cache', [RoomController::class, 'deleteCache']);

// Game controller
Route::post('start-game/{code}', [GameController::class, 'startGame']);
Route::post('redirect-game', [GameController::class, 'redirectGame']);
Route::get('get-word/{difficulty}', [GameController::class, 'getWord']);
Route::post('word', [GameController::class, 'broadcastWord']);
Route::post('round-finished', [GameController::class, 'broadcastRoundFinished']);
Route::post('correct-word', [GameController::class, 'correctWord']);
Route::post('drawer-points', [GameController::class, 'drawerPoints']);
Route::post('start-timer', [GameController::class, 'startTimer']);
Route::post('encrypted-word', [GameController::class, 'encryptedWord']);
Route::post('update-game/{game_id}', [GameController::class, 'updateGame']);

// Chat controller
Route::post('messages', [ChatsController::class, 'sendMessage']);
Route::post('canvas', [ChatsController::class, 'sendCanvas']);
Route::post('clean-canvas', [ChatsController::class, 'cleanCanvas']);

// Friendship controller
Route::post('/friends/accept/{requestId}', [FriendshipController::class, 'accept']);
Route::post('/friends/reject/{requestId}', [FriendshipController::class, 'reject']);
Route::get('/friends/requests', [FriendshipController::class, 'getFriendRequests']);
Route::post('/friends/send-request', [FriendshipController::class, 'sendRequest']);
Route::get('/friends/list', [FriendshipController::class, 'listFriends']);

// Account controller
Route::get('/account-history/{id}', [AccountController::class, 'getUserHistory']);
Route::get('/admin-history', [AccountController::class, 'getAllGames']);
Route::get('/admin-players', [AccountController::class, 'getAllUsers']);
Route::delete('/delete-user/{id}', [AccountController::class, 'deleteUser']);
Route::put('/update-user/{id}', [AccountController::class, 'updateUser']);