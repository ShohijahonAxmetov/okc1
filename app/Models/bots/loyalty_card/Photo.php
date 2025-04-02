<?php

namespace App\Models\bots\loyalty_card;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_id',
        'path',
    ];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}
