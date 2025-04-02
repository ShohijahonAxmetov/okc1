<?php

namespace App\Models\bots\loyalty_card;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'res',
    ];

    protected $casts = [
        'res' => 'array'
    ];

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
}
