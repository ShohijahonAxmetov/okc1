<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'venkon_id',
    	'discount_type',
    	'amount_type',
        'venkon_brand_id',
        'venkon_category_id',
        'venkon_product_id',
        'from_amount',
        'to_amount',
        'discount'
    ];

    protected $casts = [
        'venkon_product_id' => 'array'
    ];

    public function brand() {
        return $this->belongsTo(Brand::class);
    }
}
