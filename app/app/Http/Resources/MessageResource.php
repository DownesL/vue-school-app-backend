<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=> $this->id,
            'name'=> $this->name,
            'description'=> $this->description,
            'content'=> $this->content,
            'filename'=> $this->filename,
            'important'=> $this->important,
            'created_at'=> $this->created_at,
            'group_name'=> $this->group->name,
            'group_attr' => $this->when($this->group->users->first(), function () {
                return $this->group->users->first()->grp_attr;
            }),
            'organisation_name'=> $this->group->organisation->name,
            'message_attr'=> $this->when($this->users->first(), function () {
                return $this->users->first()->msg_attr;
            })
        ];
    }
}
