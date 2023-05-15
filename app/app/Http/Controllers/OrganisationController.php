<?php

namespace App\Http\Controllers;

use App\Models\Organisation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganisationController extends Controller
{
    public function allOrgans()
    {
        return Organisation::with('groups')->get();
    }

    public function userOrgans()
    {
        return Organisation::whereHas('roles', function ($q) {
            $q->where('id','>',1)
                ->whereHas('users', function ($p) {
                    $p->where('id', Auth::id());
                });
            })->get();
    }

    public function nonUserOrgans()
    {
        return Organisation::whereHas('roles', function ($q) {
            $q->where('id','>',1)
                ->whereDoesntHave('users', function ($p) {
                    $p->where('id', Auth::id());
                });
            })->get();
    }

    public function organ($id)
    {
        return Organisation::with(['groups','roles.users'])
            ->where('id', $id)->first();
    }


}
