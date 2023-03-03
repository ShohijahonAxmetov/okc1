<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoodpayTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'customer_phone_number',
        'transaction_id',
        'payment_url',
        'signature',
        'amount'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
