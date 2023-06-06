<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'feedback',
        'name',
        'position',
        'img',
        'for_search',
    ];

    protected $casts = [
        'feedback' => 'array',
        'position' => 'array',
    ];

    protected $appends = [
        'min_img',
        'md_img'
    ];

    public function getImgAttribute($value) {
        return isset($value) ? url('/upload/reviews').'/'.$value : null;
    }

    public function getMinImgAttribute()
    {
        if(isset($this->img)) {
            $img_name = explode('/', $this->img);
            return url('/upload/reviews/200').'/'.$img_name[count($img_name)-1];
        }
        return null;
    }

    public function getMdImgAttribute()
    {
        if(isset($this->img)) {
            $img_name = explode('/', $this->img);
            return url('/upload/reviews/600').'/'.$img_name[count($img_name)-1];
        }
        return null;
    }
}
