<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function user() {
        return User::with(['groups','roles.organisation'])->where('id',1)->first();
    }
}
