<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
    	'title',
    	'subtitle',
    	'desc',
    	'img'
    ];

    protected $casts = [
    	'title' => 'array',
    	'subtitle' => 'array',
    	'desc' => 'array'
    ];

    protected $appends = [
        'min_img',
        'md_img'
    ];

    public function getImgAttribute($value) {
    	return isset($value) ? url('/upload/posts').'/'.$value : null;
    }

    public function getMinImgAttribute()
    {
        if(isset($this->img)) {
            $img_name = explode('/', $this->img);
            return url('/upload/posts/200').'/'.$img_name[count($img_name)-1];
        }
        return null;
    }

    public function getMdImgAttribute()
    {
        if(isset($this->img)) {
            $img_name = explode('/', $this->img);
            return url('/upload/posts/600').'/'.$img_name[count($img_name)-1];
        }
        return null;
    }
}
