<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'orgName',
        'address',
        'contact',
        'orgId',
        'orgImage'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'id', 'organization_id');
    }
}
