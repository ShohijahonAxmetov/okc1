<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogOneC extends Model
{
    use HasFactory;

    protected $fillable = [
        'response_from_1c',
        'model',
        'action'
    ];
}
