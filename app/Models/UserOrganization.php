<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOrganization extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'organization_id',
        'status'
    ];

    public function userorganization_organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
}
