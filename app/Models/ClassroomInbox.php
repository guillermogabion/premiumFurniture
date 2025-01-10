<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassroomInbox extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_id',
        'user_id',
        'message_text',
        'file',
        'deadline',
        'status',
        'type',
    ];

    public function class_inbox()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
