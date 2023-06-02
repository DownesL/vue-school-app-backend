<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 * @OA\Schema(
 *      @OA\Xml(name="Organisation"),
 *      @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *      @OA\Property(property="name", type="string", readOnly="false", example="Odisee Hogeschool"),
 *      @OA\Property(property="description", type="string", readOnly="false", example="Een hogeschool in België die deel uit maakt van de KU Leuven"),
 *      @OA\Property(property="created_at", type="string", format="date-time", description="Initial creation timestamp", readOnly="true"),
 *      @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp", readOnly="true"),
 *)
 *
 *
 */
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
