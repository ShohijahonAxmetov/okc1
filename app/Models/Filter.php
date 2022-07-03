<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
    use HasFactory;

    protected $fillable = [
    	'title',
        'attribute_id'
    ];

    protected $casts = [
    	'title' => 'array',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function attribute() {
        return $this->belongsTo('App\Models\Attribute');
    }
}
