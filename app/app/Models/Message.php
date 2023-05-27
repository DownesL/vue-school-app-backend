<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'important',
        'content',
        'group_id',
        'filename',
    ];

    public $timestamps = true;

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['read', 'flagged'])
            ->as('msg_attr');
    }

    public function readMessages(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['read', 'flagged'])
            ->wherePivot('read', 1);
    }
}
