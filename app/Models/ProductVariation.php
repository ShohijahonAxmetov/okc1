<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    use HasFactory;

    protected $fillable = [
    	'product_id',
        'color_id',
    	'product_code',
    	'remainder',
    	'price',
    	'discount_price',
    	'is_default',
    	'is_available',
        'slug',
        'venkon_id',
        'is_active',
        'integration_id',

        'spic_id',
        'package_code',
        
        'express24_id',
        
        'height',
        'length',
        'width',
        'weight',
        
        'expiration_date',
        'expiration_date_comment',
        'service_life',
        'service_life_comment',
        'warranty_period',
        'warranty_period_comment',
        
        'yandex_category_id',
    ];

    protected $appends = [
        'discount_price'
    ];

    public function getDiscountPriceAttribute(): ?float
    {
        $discountPrice = null;
        $discount = Discount::where('is_active', 1)
            ->where('discount_type', 'product')
            ->where(function ($query) {
//                $query->where('venkon_product_id', [])
                $query->where('venkon_product_id', '[]')
                    ->orWhere('venkon_product_id', 'like', '%'.$this->integration_id.'%');
            })
            ->latest()
            ->first();

        if ($discount) {
            switch ($discount->amount_type) {
                case 'percent':
                    $discountPrice = $this->price * (100 - $discount->discount) / 100;
                    break;

                case 'fixed':
                    $discountPrice = $this->price - $discount->discount > 999 ? $this->price - $discount->discount : 1000;
                    break;
            }
        }

        return $discountPrice ? ceil($discountPrice) : $discountPrice;
    }
    public function productVariationImages() {
        return $this->hasMany('App\Models\ProductVariationImage');
    }

    public function product() {
        return $this->belongsTo('App\Models\Product');
    }

    public function attributeOptions() {
        return $this->belongsToMany('App\Models\AttributeOption');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot('count', 'price', 'discount_price', 'brand_discount');
    }

    public function color() {
        return $this->belongsTo(Color::class, 'color_id', 'integration_id');
    }

    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'product_variation_warehouse', 'product_variation_id', 'warehouse_id', 'integration_id', 'integration_id')->withPivot('remainder');
    }
}
