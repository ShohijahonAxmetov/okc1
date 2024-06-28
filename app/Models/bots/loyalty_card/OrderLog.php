<?php

namespace App\Models\bots\loyalty_card;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'barcode',
        'phone_number',
        'cashback',
        'date',
    ];
}
