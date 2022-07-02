<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
    	'title',
    	'desc',
    	'img',
    	'position',
    	'is_active',
        'link',
        'venkon_id'
    ];

    protected $casts = [
    	'desc' => 'array'
    ];

    public function getImgAttribute($value) {
    	return isset($value) ? url('/upload/brands').'/'.$value : null;
    }

    public function discount() {
        return $this->hasOne(Discount::class);
    }

    public function products() {
        return $this->hasMany('App\Models\Product', 'brand_id', 'venkon_id');
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value, '-');
    }
}
