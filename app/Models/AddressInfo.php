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
    ];

    protected $casts = [
        'address' => 'array',
    ];
}
