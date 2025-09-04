<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'venue_name' => $this->venue_name,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'pincode' => $this->pincode,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'status' => $this->status,
            'max_attendees' => $this->max_attendees,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // Include related models if needed
            'community' => new CommunityResource($this->whenLoaded('community')),
            'creator' => new UserResource($this->whenLoaded('creator')),
            'attendees_count' => $this->whenLoaded('attendees', fn() => $this->attendees->count()),
        ];
    }
}
