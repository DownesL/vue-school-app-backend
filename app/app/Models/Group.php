<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Group extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'organisation_id'
    ];

    public $timestamps = true;


    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['colour', 'alias'])
            ->as('grp_attr');
    }
    public function joinRequests(): HasMany
    {
        return $this->hasMany(JoinRequest::class);
    }
    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }
    public function messages(): Hasmany
    {
        return $this->hasMany(Message::class);
    }
}
