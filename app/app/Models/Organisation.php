<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organisation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public $timestamps = true;

    public function admins(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->as('admins');
    }
    public function joinRequests(): HasMany
    {
        return $this->hasMany(JoinRequest::class);
    }
    public function groups(): HasMany
    {
        return $this->hasMany(Group::class);
    }
}
