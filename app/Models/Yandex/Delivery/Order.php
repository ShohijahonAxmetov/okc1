<?php

namespace App\Models\Yandex\Delivery;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'yandex_delivery_orders';

    protected $fillable = [
        'order_id',
        'a_lat',
        'a_lon',
        'b_lat',
        'b_lon',
        'preliminary_amount',
        'status',

        'loading_point_id',
        'claim_res',
        'claim_status',
        'claim_id',
        'request_id',
        'is_actual',
    ];

    protected $appends = [
        'claim_res' => 'array'
    ];

    public function order()
    {
        return $this->belongsTo(\App\Models\Order::class);
    }

    public function loadingPoint()
    {
        return $this->belongsTo(\App\Models\LoadingPoint::class);
    }
}
