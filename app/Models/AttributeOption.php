<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeOption extends Model
{
    use HasFactory;

    protected $fillable =[
    	'attribute_id',
    	'key',
    	'value'
    ];

    protected $casts = [
    	'key' => 'array'
    ];

    public function attribute() {
    	return $this->belongsTo('App\Models\Attribute');
    }

    public function productVariation() {
        return $this->belongsToMany('App\Models\ProductVariation');
    }
}
