<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;


    protected $fillable = [
        'date',
        'product_ids',
        'quantity',
        'total',
        'user_id',
        'status',
        'ref_no',
        'payment_mode',
        'image',
        'orderId',
        'downpayment_amount'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
