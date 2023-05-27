<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupAdminResource extends JsonResource
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
            'organisation_name' => $this->organisation->name,
            'messages' => MessageResource::collection(
                $this->messages()
                    ->orderByDesc('created_at')
                    ->limit(3)
                    ->get()),
            'members' => $this->users,
            'join_requests' => JoinRequestResource::collection($this->joinRequests->where('status', 'PENDING')),
            'admin' => true
        ];
    }
}
