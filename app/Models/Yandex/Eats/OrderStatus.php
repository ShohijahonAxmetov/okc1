<?php

namespace App\Models\Yandex\Eats;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;

    protected $table = 'yandex_eats_order_status_changes';

    protected $fillable = [
        'order_id',
        'status',
        'comment',
        'packages_count',
        'reason',
        'platform',
        'attributes',
        'updated_at',
    ];

    protected $casts = [
        'attributes' => 'array'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
