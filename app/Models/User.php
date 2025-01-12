<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use League\CommonMark\Node\Block\Document;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'profile',
        'fullname',
        'email',
        'contact',
        'gender',
        'address',
        'isVendor',
        'shop_name',
        'type',
        'role',
        'password',
        'status',
        'isReset'
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function position()
    {
        return $this->belongsTo(Positions::class, 'id', 'user_id');
    }
    public function candidates()
    {
        return $this->belongsTo(Candidate::class, 'id', 'user_id');
    }
    public function user_classroom()
    {
        return $this->hasMany(StudentClassroom::class, 'user_id', 'id');
    }

    public function details()
    {
        return $this->hasOne(Details::class, 'user_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'cart', 'user_id', 'product_id');
    }
    public function product()
    {
        return $this->belongsToMany(Product::class,  'user_id');
    }

    public function sellers()
    {
        return $this->hasMany(Product::class, 'user_id');
    }


    public function document()
    {
        return $this->hasOne(SupportDocument::class, 'user_id');
    }
    public function gcash()
    {
        return $this->hasOne(GcashQrimage::class);
    }
}
