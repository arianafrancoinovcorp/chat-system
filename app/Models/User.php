<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Message;
use App\Models\Room;


class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function rooms()
    {
        return $this->belongsToMany(Room::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'to_user_id');
    }

    /**
     * Unread Messages count
     */
    public function unreadMessagesCount()
    {
        return Message::where('to_user_id', $this->id)
                      ->whereNull('read_at')
                      ->count();
    }

    /**
     *  Unread Messages count with a specific user
     */
    public function unreadMessagesFrom($userId)
    {
        return Message::where('to_user_id', $this->id)
                      ->where('user_id', $userId)
                      ->whereNull('read_at')
                      ->count();
    }

    /**
     * Last message
     */
    public function lastMessageWith($userId)
    {
        return Message::where(function($q) use ($userId) {
            $q->where('user_id', $this->id)
              ->where('to_user_id', $userId);
        })->orWhere(function($q) use ($userId) {
            $q->where('user_id', $userId)
              ->where('to_user_id', $this->id);
        })
        ->latest()
        ->first();
    }
}
