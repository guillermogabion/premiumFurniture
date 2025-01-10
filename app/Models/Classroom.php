<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'classId',
        'class_schedule',
        'class_instructor_id',
        'class_subject',
        'class_other'
    ];

    public function class_classroom()
    {
        return $this->hasMany(StudentClassroom::class, 'classroom_id', 'id');
    }
}
