<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FriendRequest extends Model
{
    use HasFactory;

    protected $table = 'friend_request';
    protected $fillable = ['user_id_1', 'user_id_2'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id_1');
    }
}
