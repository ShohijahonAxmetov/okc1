<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddressInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
        'iframe',
        'phone_number',
    ];

    protected $casts = [
        'address' => 'array',
    ];
}
