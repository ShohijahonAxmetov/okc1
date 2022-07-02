<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = [
    	'title',
    	'is_active'
    ];

    protected $casts = [
    	'title' => 'array'
    ];

    public function attributeOptions() {
    	return $this->hasMany('App\Models\AttributeOption');
    }

    public function filter() {
        return $this->hasMany('App\Models\Filter');
    }
}
