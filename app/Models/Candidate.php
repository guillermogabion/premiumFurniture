<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'position_id',
        'status',
        'count'
    ];


    public function user_candidates()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function candidate_position()
    {
        return $this->belongsTo(Positions::class, 'position_id');
    }
}
