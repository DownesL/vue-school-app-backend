<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
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
        'first_name',
        'last_name',
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
    ];

    public $timestamps = true;

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class)
            ->withPivot(['colour', 'alias'])
            ->as('grp_attr');
    }

    public function messages(): BelongsToMany
    {
        return $this->belongsToMany(Message::class)
            ->withPivot(['read', 'flagged'])
            ->as('msg_attr');
    }
    public function readMessages() : BelongsToMany {
        return $this->belongsToMany(Message::class)
            ->wherePivot('read', 1);
    }

    public function flaggedMessages(): BelongsToMany
    {
        return $this->belongsToMany(Message::class)
            ->withPivot(['read', 'flagged'])
            ->wherePivot('flagged', 1);
    }

    public function organisations(): BelongsToMany
    {
        return $this->belongsToMany(Organisation::class);
    }

    public function joinRequests(): HasMany
    {
        return $this->hasMany(JoinRequest::class);
    }
}
