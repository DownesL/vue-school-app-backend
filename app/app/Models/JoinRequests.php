<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JoinRequests extends Model
{
    use HasFactory;
    public function group() {
        return $this->belongsTo('groups');
    }
    public function organisation() {
        return $this->belongsTo('organisation');
    }
    public function user() {
        return $this->belongsTo('user');
    }

}
