<?php

namespace App\Models\Yandex\Eats;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'yandex_eats_orders';

    protected $fillable = [
        'brand',
        'comment',
        'delivery_info',
        'discriminator',
        'eats_id',
        'items',
        'payment_info',
        'platform',
        'restaurant_id',
    ];

    protected $casts = [
        'delivery_info' => 'array',
        'payment_info' => 'array',
        'items' => 'array',
    ];

    public function statusChanges()
    {
        return $this->hasMany(OrderStatus::class);
    }
}
