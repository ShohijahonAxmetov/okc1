<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
    	'brand_id',
    	'title',
    	'desc',
        'how_to_use',
    	'meta_keywords',
    	'is_active',
        'is_popular',
        'vendor_code',
        'rating',
        'number_of_ratings',

        'meta_title',
        'meta_desc',
    ];

    protected $casts = [
    	'title' => 'array',
    	'desc' => 'array',
    	'meta_keywords' => 'array',
        'how_to_use' => 'array',
        
        'meta_title' => 'array',
        'meta_desc' => 'array',
    ];

    protected $appends = [
        'default_variation',
        'remainder'
    ];

    public function getRemainderAttribute()
    {
        return $this->getDefaultVariationAttribute()->remainder ?? 0;
    }

    public function brand()
    {
    	return $this->belongsTo('App\Models\Brand', 'brand_id', 'integration_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product', 'product_id', 'category_id', 'id', 'integration_id');
    }

    public function productVariations() {
        return $this->hasMany('App\Models\ProductVariation');
    }

    public function getDefaultVariationAttribute() {
        return $this->productVariations()->where('is_default', 1)->with('productVariationImages')->first();
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

}
