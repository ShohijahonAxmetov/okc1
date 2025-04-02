<?php

namespace App\Models\bots\loyalty_card;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'telegram_id',
        'is_bot',
        'first_name',
        'last_name',
        'username',
        'is_premium',
        'added_to_attachment_menu',

        'name',
        'user_id',
    ];

    public function tests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->belongsTo(User::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
