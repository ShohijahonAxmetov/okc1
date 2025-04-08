<?php

namespace App\Models\Yandex;

use App\Models\Category;
use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'integration_id',
        'name',
        'parent_integration_id',
    ];

    protected $appends = [
        'category'
    ];

    public function integrationParent()
    {
        return $this->belongsTo(self::class, 'integration_id', 'parent_integration_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_integration_id', 'integration_id');
    }

    public function getCategoryAttribute()
    {
        $categoryMiddleItem = DB::table('category_market_category')->where('market_category_id', $this->integration_id)->first();

        return $categoryMiddleItem ? Category::find($categoryMiddleItem->category_id) : null;
    }
}
