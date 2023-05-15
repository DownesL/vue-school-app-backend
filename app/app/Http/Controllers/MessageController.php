<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\MessageResource;
use Carbon\Carbon;

class MessageController extends Controller
{
    public function all()
    {
//       /* return User::where('id',Auth::id())
//            ->with(['messages','messages.group','messages.group.users'=>function ($q) {
//                $q->find(Auth::id(),['id']);
//            }]) // TODO: FOREACH GROUP => APPEND TO MESSAGE LIST
//            ->first()->messages;*/
//        $groups = User::with([
//            'groups.messages:id,name,important,created_at,group_id',
//            'groups.messages.group:id,name,organisation_id',
//            'groups.messages.group.organisation:id,name'
//        ])
//
//            ->findOrFail(1)->groups;
//        $messages = [];
//        foreach($groups as $group) {
//            foreach($group->messages as $message) {
//                $message['custom_traits'] = $group['custom_traits'];
//                $messages[] = $message;
//            }
//        }
//        return $messages;
        return MessageResource::collection(Message::whereHas('group.users', function($q) {
                $q->where('id', Auth::id());
            })
            ->orderByDesc('created_at')
            ->get()
        );
    }

    public function unread()
    {
        /*$msgArr = User::where('id',Auth::id())->with('messages:id')->first('id')->messages;
        $readArr = [];
        foreach ($msgArr as $msg) {
            if ($msg->pivot->read)
                {
                    $readArr[] = $msg->id;
                }

        }
        return User::where('id',Auth::id())
            ->with([
                'groups:id,name,organisation_id',
                'groups.organisation:id,name',
                'groups.messages'
                => function($q) use ($readArr) {
                    $q->whereNotIn('id',$readArr)
                    ->select(['id','name','group_id','important']);
                },
            ])
            ->first();*/
        /*return Message::whereHas('group.users',function($q) {
            $q->where('id', Auth::id())->whereHas('messages');
        } )
            ->with('group.organisation','users:id','group.users')
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();*/

        return MessageResource::collection(Message::whereHas('group.users',function($q) {
                $q->where('id', Auth::id())->whereHas('messages');
            })
            ->orderByDesc('created_at')
            ->limit(3)
            ->get()
        );
    }


    public function flagged() {
        return User::where('id',Auth::id())
            ->with([
                'flaggedMessages.group.organisation'
            ])
            ->first()->flaggedMessages;
    }

    public function specific($id) {
        $msg = User::where('id',Auth::id())
            ->with([
                'groups.messages' => function($q) use ($id) {
                    $q->where('id',$id)
                        ->with(['group:id,name,organisation_id','group.organisation:id,name']);
                },
            ])
            ->first();
        if ($msg && count($msg->groups) && count($msg->groups[0]->messages)) {
            return $msg->groups[0]->messages[0];
        }
        return abort(response()->json(['message'=>'You do not have the right to view this page'],401));
    }
}
