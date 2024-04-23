<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    // La tabla asociada con el modelo.
    protected $table = 'game';

    // Los atributos que son asignables masivamente.
    protected $fillable = [
        'rounds',
        'time_per_round',
        'dificulty'
    ];

    // Los atributos que deben ser ocultados para los arrays.
    protected $hidden = [
        'updated_at',
    ];

    // Los atributos que deberían ser convertidos a tipos nativos.
    protected $casts = [
        'rounds' => 'integer',
        'time_per_round' => 'integer',
    ];
}
