<?php

namespace App\Http\Controllers;

use App\Http\Resources\MessageResource;
use App\Mail\MessageFlagged;
use App\Models\Message;
use App\Models\Organisation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class MessageController extends Controller
{
    public function all()
    {
        return MessageResource::collection(
            Message::whereHas('group.users', function ($q) {
                $q->where('id', Auth::id());
            })
                ->orderByDesc('created_at')
                ->get()
        );
    }

    public function recent()
    {
        return MessageResource::collection(
            Message::whereHas('group.users', function ($q) {
                $q->where('id', Auth::id());
            })
                ->orderByDesc('created_at')
                ->limit(3)
                ->get()
        );
    }


    public function flagged()
    {
        return MessageResource::collection(User::where('id', Auth::id())
            ->with([
                'flaggedMessages.group.organisation'
            ])
            ->first()->flaggedMessages);
    }

    public function specific(Message $message)
    {
        if (!Auth::user()->whereHas('readMessages', function ($q) use ($message) {
            $q->where('id', $message->id);
        })->exists()) {
            Auth::user()->messages()->attach($message->id, ['read' => 1]);
        }
        return new MessageResource(Auth::user()->messages()->find($message->id));
    }

    public function createMessage(Organisation $organisation, Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:organisations'],
            'description' => ['required', 'string', 'max:255'],
            'important' => ['string', 'in:true,false'],
            'groups' => ['required', 'array', 'min:1'],
            'groups.*' => ['required', 'string', Rule::in($organisation->groups->map(fn($grp) => $grp->name))],
            'message' => ['required_if:message_file,null', 'string'],
            'filename' => ['required_if:message,null', 'file', File::types('pdf')->max(12 * 1024)]
        ]);

        $groups = $organisation->groups()->whereIn('name', $request->groups)->get();
        $path = null;
        if ($request->filename) {
            $path = $request->file('filename')->storeAs(
                'messages', preg_replace('/\W+/', '', ($request->name . Carbon::now())) . '.pdf'
            );
        }
        foreach ($groups as $group) {
            $msg = Message::create([
                'name' => $request->name,
                'description' => $request->description,
                'important' => $request->important === 'true' ? 1 : 0,
                'content' => $request->message,
                'group_id' => $group->id,
                'filename' => $path
            ]);
        }

        return response(['message' => 'The Message has been created successfully', 'organisation_id' => $organisation->id], 200);
    }

    public function flag(Message $message)
    {
        $msg = Auth::user()->messages()->findOrFail($message->id);
        $value = !$msg->msg_attr->flagged;
        Auth::user()->messages()->updateExistingPivot($msg->id, ['flagged' => $value]);
        if ($value) {
            Mail::to(Auth::user())->send(new MessageFlagged($message));
        }
        return new MessageResource(Auth::user()->messages()->find($message->id));
    }
}
