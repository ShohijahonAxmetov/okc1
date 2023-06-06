<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'main_text',
        'text',
        'video',
        'meta_title',
        'meta_desc',
        'meta_keywords',
    ];

    protected $casts = [
        'main_text' => 'array',
        'text' => 'array',
        'meta_title' => 'array',
        'meta_desc' => 'array',
        'meta_keywords' => 'array',
    ];

    public function images()
    {
        return $this->hasMany(PageImage::class);
    }

    public function getVideoAttribute($value) {
        return isset($value) ? url('/upload/pages').'/'.$value : null;
    }
}
