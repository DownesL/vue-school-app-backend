<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'content' => $this->content,
            'filename' => $this->filename,
            'important' => $this->important,
            'created_at' => $this->created_at,
            'group_name' => $this->group->name,
            'group_attr' => $this->when($this->group->users->find(Auth::id()), function () {
                return $this->group->users->find(Auth::id())->grp_attr->makeHidden(['group_id','user_id']);
            }),
            'organisation_name' => $this->group->organisation->name,
            'message_attr' => $this->when($this->users->find(Auth::id()), function () {
                return $this->users->find(Auth::id())->msg_attr->makeHidden(['message_id','user_id']);
            })
        ];
    }
}
