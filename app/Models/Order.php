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
		'payment_card',
    	'status',
        'is_deleted',
        'is_payed',
        'warehouse_id',

        'delivery_type',
        'delivery_price',
    ];

    public function productVariations()
    {
        return $this->belongsToMany(ProductVariation::class)->withPivot('count', 'price', 'discount_price', 'brand_discount');
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function zoodpayTransaction()
    {
        return $this->hasOne(ZoodpayTransaction::class);
    }

    public function zoodpayHistories()
    {
        return $this->hasMany(ZoodpayHistory::class);
    }

    public function playmobileMessages()
    {
        return $this->hasMany(PlaymobileMessage::class);
    }

    public function fargoHistories()
    {
        return $this->hasMany(FargoHistory::class, 'reference_id');
    }
}
