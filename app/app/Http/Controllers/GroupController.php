<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupAdminResource;
use App\Http\Resources\GroupUserResource;
use App\Models\Group;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
                $q->where('id', Auth::id());
            })->get();
    }

    public function group(Group $group)
    {
        if (Auth::user()->organisations()->where('id', $group->organisation->id)->exists()) {
            return new GroupAdminResource($group);
        }
        return new GroupUserResource($group);
    }

    public function create(Organisation $organisation, Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('groups')->where('organisation_id', $organisation->id)],
            'description' => ['required', 'string', 'max:255'],
        ]);
        $group = Group::create([
            'name' => $request->name,
            'description' => $request->description,
            'organisation_id' => $organisation->id
        ]);
        return response(['message' => 'The group has been created successfully', 'group_id' => $group->id], 200);
    }

    public function setAttributes(Group $group, Request $request)
    {
        $request->validate([
            'alias' => ['string', 'nullable', 'max:50'],
            'colour' => ['nullable', 'regex:/#[0-9a-fA-F]{6}/']
        ]);
        Auth::user()->groups()->updateExistingPivot($group->id, ['alias' => $request->alias, 'colour' => $request->colour]);
        return response(['message' => 'Group attribute successfully updated.']);
    }
}
