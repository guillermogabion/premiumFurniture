<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomMessages extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'seller_id',
        'room_id',
        'message',
        'image',
        'isRead'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
