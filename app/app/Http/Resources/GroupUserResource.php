<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class GroupUserResource extends JsonResource
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
            'organisation_name' => $this->organisation->name,
            'messages' => MessageResource::collection(
                $this->messages()
                    ->orderByDesc('created_at')
                    ->limit(3)
                    ->get()),
            'group_attr' => $this->when($this->users->find(Auth::id()), function () {
                return $this->users->find(Auth::id())->grp_attr->makeHidden(['group_id','user_id']);
            }),
        ];
    }
}
