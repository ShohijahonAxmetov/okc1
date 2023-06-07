<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'meta_title',
        'meta_desc',
        'meta_keywords',
        'facebook',
        'telegram',
        'instagram',
        'youtube',
        'phone_number',
        'dop_phone_number',
    ];

    protected $casts = [
        'meta_title' => 'array',
        'meta_desc' => 'array',
        'meta_keywords' => 'array'
    ];
}
