<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrganisationResource;
use App\Models\Organisation;
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
        return Organisation::whereHas('admins', function ($q) {
            $q->where('id', Auth::id());
        })->get();
    }

    public function nonUserOrgans()
    {
        return Organisation::whereDoesntHave('admins', function ($p) {
            $p->where('id', Auth::id());
        })->get();
    }

    public function organ(Organisation $organisation)
    {
        return new OrganisationResource($organisation);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:organisations'],
            'description' => ['required', 'string', 'max:255'],
        ]);
        $organ = Organisation::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        Auth::user()->organisations()->attach($organ);
        return response(['message' => 'The Organisation has been created successfully', 'organisation_id' => $organ->id], 200);
    }


}
