<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Get the authenticated user if available
        $user = $request->user();
        
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'type' => $this->type,
            'published_at' => $this->published_at?->toIso8601String(),
            'is_pinned' => $this->is_pinned,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'like_count' => $this->likes()->count(),
            'user_has_liked' => $user ? $this->likes()->where('user_id', $user->id)->exists() : false,
            'author' => [
                'id' => $this->author->id,
                'name' => $this->author->name,
                // Add other user fields as needed
            ],
            'community' => [
                'id' => $this->community->id,
                'name' => $this->community->name,
                // Add other community fields as needed
            ],
            'images' => $this->getMedia('images')->map(function ($media) {
                return [
                    'id' => $media->id,
                    'url' => $media->getUrl(),
                    'name' => $media->name,
                    'mime_type' => $media->mime_type,
                ];
            }),
        ];
    }
}