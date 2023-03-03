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
        'integration_id'
    ];

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
