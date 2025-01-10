<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Details extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'organization_id',
        'profile',
        'status',
        'user_id',
        'organization_id'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
    public function users_id()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
