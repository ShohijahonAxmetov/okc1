<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'img',
        'page_id',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function getImgAttribute($value) {
        return isset($value) ? url('/upload/products').'/'.$value : null;
    }
}
