<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariationImage extends Model
{
    use HasFactory;

    protected $fillable = [
    	'product_variation_id',
    	'img'
    ];

    protected $appends = [
        'min_img',
        'md_img',
        'real_img',
    ];

    public function getMinImgAttribute()
    {
        if(isset($this->img)) {
            $img_name = explode('/', $this->img);
            return url('/upload/products/200').'/'.$img_name[count($img_name)-1];
        }
        return null;
    }

    public function getMdImgAttribute()
    {
        if(isset($this->img)) {
            $img_name = explode('/', $this->img);
            return url('/upload/products/600').'/'.$img_name[count($img_name)-1];
        }
        return null;
    }

    public function getRealImgAttribute() {
        if (isset($this->img)) {
            $exploded = explode('/', $this->img);
            $imploded = implode('/', array_slice($exploded, 3));

            $result = 'https://admin.okc.uz/'.$imploded;
        } else $result = $this->img;

        return $result;
    } 

    public function getImgAttribute($value) {
        return isset($value) ? url('/upload/products').'/'.$value : null;
    }   

    public function productVariation() {
    	return $this->belongsTo('App\Models\ProductVariation');
    }
}
