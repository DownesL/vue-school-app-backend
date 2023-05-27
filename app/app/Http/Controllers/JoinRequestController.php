<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupAdminResource;
use App\Http\Resources\OrganisationResource;
use App\Models\Group;
use App\Models\JoinRequest;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class JoinRequestController extends Controller
{
    public function joinGroup(Request $request, Group $group)
    {
        $request->validate([
            'motivation' => ['required', 'string', 'min:15', 'max:200'],
            'id' => [
                'required',
                Rule::in(Group::get('id')->map(fn($o) => $o->id)),
                Rule::notIn(Auth::user()->groups()->get(['id'])->map(fn($g) => $g->id))]
        ]);

        $jr = Auth::user()->whereHas('joinRequests', function ($q) use ($group) {
            $q->where('group_id', $group->id);
        });
        if ($jr->exists()) {
            $jr = $jr->first()->joinRequests->where('group_id', $group->id)->first();
            return response(['message' => "The joinrequest has status: $jr->status"], 400);
        }
        JoinRequest::create([
            'user_id' => Auth::id(),
            'motivation' => $request->motivation,
            'group_id' => $group->id,
            'status' => 'PENDING'
        ]);
        return response(['message' => 'Join request created successfully']);
    }

    public function joinOrganisation(Request $request, Organisation $organisation)
    {
        $request->validate([
            'motivation' => ['required', 'string', 'min:15', 'max:200'],
            'id' => [
                'required',
                Rule::in(Organisation::get('id')->map(fn($o) => $o->id)),
                Rule::notIn(Auth::user()->organisations()->get(['id'])->map(fn($o) => $o->id)
                )]
        ]);

        $jr = Auth::user()->with('joinRequests')->whereHas('joinRequests', function ($q) use ($organisation) {
            $q->where('organisation_id', $organisation->id);
        });
        if ($jr->exists()) {
            $jr = $jr->first()->joinRequests->where('organisation_id', $organisation->id)->first();
            return response(['message' => 'The satus of the join request is: ', 'jr' => $jr->status], 400);
        }
        $r = JoinRequest::create([
            'user_id' => Auth::id(),
            'motivation' => $request->motivation,
            'organisation_id' => $organisation->id,
            'status' => 'PENDING'
        ]);
        return response(['message' => "Join request now has status: $r->status", 'jr' => $r]);
    }

    public function acceptRequest(JoinRequest $joinRequest)
    {
        $joinRequest->status = 'APPROVED';
        $joinRequest->save();
        $user = User::find($joinRequest->user_id);
        if ($joinRequest->organisation_id) {
            Organisation::find($joinRequest->organisation_id)->admins()->attach($user);
            return new OrganisationResource($joinRequest->organisation);
        }
        Organisation::find($joinRequest->group->organisation_id)->admins()->attach($user);
        return OrganisationController::organ($joinRequest->group->organisation_id);
    }

    public function denieRequest(JoinRequest $joinRequest)
    {
        $joinRequest->status = 'DENIED';
        $joinRequest->save();
        if ($joinRequest->organisation_id)
            return new OrganisationResource($joinRequest->organisation);
        return new GroupAdminResource(Group::find($joinRequest->group_id));
    }
}
