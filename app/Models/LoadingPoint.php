<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoadingPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'desc',
        'lat',
        'lon',
        
        'address',
        'comments',
    ];
}
