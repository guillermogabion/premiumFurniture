<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentClassroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'classroom_id',
        'status'
    ];

    public function user_studentclassroom()
    {
        return $this->belongsTo(Details::class, 'user_id', 'user_id')->with('users_id');
    }
    public function classroom_studentclassroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }
}
