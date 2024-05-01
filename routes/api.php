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
    Route::apiResource('posts', PostController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('roles', RoleController::class);
    //Route::apiResource('exercises', ExerciseController::class);
    Route::post('exercises/', [ExerciseController::class,'store']); //Guardar
    Route::get('exercises', [ExerciseController::class,'index']); //Listar
    Route::get('exercises/{exercise}', [ExerciseController::class,'show']); //Mostrar
    Route::post('exercises/update/{id}', [ExerciseController::class,'update']); //Editar

    Route::get('role-list', [RoleController::class, 'getList']);
    Route::get('role-permissions/{id}', [PermissionController::class, 'getRolePermissions']);
    Route::put('/role-permissions', [PermissionController::class, 'updateRolePermissions']);
    Route::apiResource('permissions', PermissionController::class);
    Route::get('category-list', [CategoryController::class, 'getList']);
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


Route::get('category-list', [CategoryController::class, 'getList']);
Route::get('get-posts', [PostController::class, 'getPosts']);
Route::get('get-category-posts/{id}', [PostController::class, 'getCategoryByPosts']);
Route::get('get-post/{id}', [PostController::class, 'getPost']);

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

// Game controller
Route::get('get-user', [GameController::class, 'getUserData']);
Route::post('start-game/{code}', [GameController::class, 'startGame']);
Route::post('redirect-game', [GameController::class, 'redirectGame']);
Route::get('get-word/{difficulty}', [GameController::class, 'getWord']);
Route::post('word', [GameController::class, 'broadcastWord']);

// Chat controller
Route::post('messages', [ChatsController::class, 'sendMessage']);
Route::post('canvas', [ChatsController::class, 'sendCanvas']);

// Friendship controller
Route::post('/friends/accept/{requestId}', [FriendshipController::class, 'accept']);
Route::post('/friends/reject/{requestId}', [FriendshipController::class, 'reject']);
Route::get('/friends/requests', [FriendshipController::class, 'getFriendRequests']);
Route::post('/friends/send-request', [FriendshipController::class, 'sendRequest']);
Route::get('/friends/list', [FriendshipController::class, 'listFriends']);

// Account controller
Route::get('/account-history/{id}', [AccountController::class, 'getUserHistory']);