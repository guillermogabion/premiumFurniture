<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GcashQrimage extends Model
{
    use HasFactory;

    protected $table = 'gcash_qrimages';

    // Define the fillable columns (add other columns as needed)
    protected $fillable = ['user_id', 'gcash_qr_code'];
}
