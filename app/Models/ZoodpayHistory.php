<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoodpayHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'order_id',
        'status',
        'amount',
        'signature'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
