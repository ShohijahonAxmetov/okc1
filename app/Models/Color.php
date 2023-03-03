<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    protected $fillable = [
        'venkon_id', 
        'title',
        'hex_code',
        'is_active',
        'integration_id'
    ];

    protected $casts = [
        'title' => 'array'
    ];

    public function productVariation() {
        return $this->hasOne(ProductVariation::class, 'color_id', 'integration_id');
    }
}
