<?php

namespace App\Http\Controllers;

use App\Models\Group;

class FiddleController extends Controller
{
    public function main()
    {
        /*return Group::with(['organisation', 'users'])
            ->whereHas('users', function ($q) {
                $q->where('id', 1);
            })->get();*/
        return Group::with(['organisation','messages','users'])
            ->whereHas('users', function ($q) {
                $q->where('id', 1);
            })->get();
    }
}
