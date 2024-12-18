<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpressConfig extends Model
{
    use HasFactory;

    protected $fillable = [
    	'price_up',
    	'vat',
    	'products_min_count',
    ];
}
