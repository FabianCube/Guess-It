<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    // Nombre de la tabla asociada con el modelo
    protected $table = 'history';

    // Campos asignables masivamente
    protected $fillable = [
        'game_id',
        'user_id',
        'user_points',
        'user_position'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'anonymous_user_id' => 'string',
    ];

    // Relación con el modelo Game
    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    // Relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function getAllGames()
    {
        return self::all();
    }
}
