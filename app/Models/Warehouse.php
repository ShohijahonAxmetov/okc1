<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'is_active',
        'venkon_id',
        'integration_id',
        'is_store',
        'for_fargo',
        
        'express24_id',
    ];

    public function productVariations()
    {
        return $this->belongsToMany(ProductVariation::class, 'product_variation_warehouse', 'warehouse_id', 'product_variation_id', 'integration_id', 'integration_id')->withPivot('remainder');
    }
}
