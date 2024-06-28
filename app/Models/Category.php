<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
    	'title',
    	'parent_id',
    	'img',
    	'desc',
    	'meta_keywords',
    	'meta_desc',
        'venkon_id',
        'is_active',
        'position',
        'integration_id',
        
        'express24_id',
    ];

    protected $casts = [
    	'title' => 'array',
    	'desc' => 'array',
    	'meta_keywords' => 'array',
    	'meta_desc' => 'array'
    ];

    protected $appends = [
        'parents'
    ];

    public function getImgAttribute($value) {
        return isset($value) ? url('/upload/categories').'/'.$value : null;
    }

    public function filters()
    {
        return $this->belongsToMany(Filter::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_product', 'category_id', 'product_id', 'integration_id', 'id');
    }

    public function parent()
    {
        return $this->hasOne(self::class, 'integration_id', 'parent_id');
    }
    
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'integration_id');
    }

    public function getParentsAttribute()
    {
        $parents = collect([]);

        $parent = $this->parent;

        while(!is_null($parent)) {
            $parents->push($parent);
            $parent = $parent->parent;
        }

        // return $parents;
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = json_encode($value);
        $this->attributes['slug'] = Str::slug($value->ru, '-');
    }
}
