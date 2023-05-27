<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JoinRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'organisation_id',
        'user_id',
        'status',
        'motivation'
    ];
    public $timestamps = true;
    public function group() {
        return $this->belongsTo(Group::class);
    }
    public function organisation() {
        return $this->belongsTo(Organisation::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }

}
