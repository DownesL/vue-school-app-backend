<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Message extends Model
{
    use HasFactory;

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class);
    }
    public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot(['read','flagged']);
    }
}
