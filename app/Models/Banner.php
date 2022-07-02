<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
    	'link',
    	'img',
    	'is_active'
    ];

    public function getImgAttribute($value) {
    	return isset($value) ? url('/upload/banners').'/'.$value : null;
    }
}
