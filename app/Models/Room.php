<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'seller_id',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
    public function user_customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}