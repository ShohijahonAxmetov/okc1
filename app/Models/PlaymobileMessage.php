<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlaymobileMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'phone_number',
        'text',
        'error_code',
        'error_description'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
