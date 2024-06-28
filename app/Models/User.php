<?php

namespace App\Models;

use App\Models\bots\loyalty_card\TelegramUser;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'district',
        'region',
        'address',
        'postal_code',
        'username',
        'password',
        'img',
        'sex',
        'barcode',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'img_url'
    ];

    public function getImgUrlAttribute() {
        if(isset($this->img)) {
            $img_name = explode('/', $this->img);
            return url('/upload/users/200').'/'.$img_name[count($img_name)-1];
        }
        return null;
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function telegramUser()
    {
        return $this->hasOne(TelegramUser::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
