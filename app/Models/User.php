<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
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
        'password',
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function messages()
    {
        return $this->hasMany(Message::class, 'from_user_id')
            ->orWhere('to_user_id', $this->id);
    }

    public function unreadMessages()
    {
        return $this->hasMany(Message::class, 'from_user_id')
            ->where('to_user_id', auth()->id())
            ->whereNull('read_at');
    }

    public function getLastMessageAttribute()
    {
        return Message::where(function($query) {
                $query->where('from_user_id', $this->id)
                      ->where('to_user_id', auth()->id());
            })
            ->orWhere(function($query) {
                $query->where('from_user_id', auth()->id())
                      ->where('to_user_id', $this->id);
            })
            ->latest()
            ->first();
    }

    public function hasUnreadMessages()
    {
        return Message::where('from_user_id', $this->id)
            ->where('to_user_id', auth()->id())
            ->whereNull('read_at')
            ->exists();
    }
}
