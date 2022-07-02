<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
    	'user_id',
    	'brand_discount',
    	'amount',
    	'name',
    	'phone_number',
    	'email',
    	'district',
    	'region',
    	'postal_code',
    	'address',
    	'comments',
    	'with_delivery',
    	'delivery_method',
    	'payment_method',
    	'status',
        'is_deleted',
        'is_payed'
    ];

    public function productVariations()
    {
        return $this->belongsToMany(ProductVariation::class)->withPivot('count', 'price', 'discount_price', 'brand_discount');
    }

    public function user() {
    	return $this->belongsTo('App\Models\User');
    }
}
