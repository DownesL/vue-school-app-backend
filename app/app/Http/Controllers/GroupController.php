<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function allGroups()
    {
        return Group::with('organisation')->get();
    }

    public function userGroups()
    {
        return User::with('groups.organisation')
            ->findOrFail(Auth::id())->groups;
    }

    public function nonUserGroups()
    {
        return Group::with(['organisation'])
            ->whereDoesntHave('users', function ($q) {
                $q->where('id', 1);
            })->get();
    }
    public function group($id) {
        return User::with(['groups.messages.group.organisation','groups.organisation','groups.users','groups' => function($q) use ($id) {
                $q->find($id);
            }])->findOrFail(Auth::id())->groups[0];

        // TODO: RETURN NO ALIAS IF USER IS ADMIN
        return Group::where('id', $id)
            ->with(['messages.group.organisation','organisation','users' => function($q) {
                $q->find(Auth::id());
            }])
            ->first();
    }
}
