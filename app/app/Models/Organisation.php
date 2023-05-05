<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organisation extends Model
{
    use HasFactory;

    public function roles(): HasMany
    {
        return $this->hasMany(Role::class);
    }
    public function joinRequests(): HasMany
    {
        return $this->hasMany(JoinRequests::class);
    }
    public function groups(): HasMany
    {
        return $this->hasMany(Group::class);
    }
}
