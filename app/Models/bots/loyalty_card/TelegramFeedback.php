<?php

namespace App\Models\bots\loyalty_card;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramFeedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'telegram_id',
        'username',
        'message',
        'phone_number',
    ];

    public function telegramUser()
    {
        return $this->belongsTo(TelegramUser::class, 'telegram_id', 'telegram_id');
    }
}
