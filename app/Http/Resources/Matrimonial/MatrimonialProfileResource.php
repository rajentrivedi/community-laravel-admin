<?php

namespace App\Http\Resources\Matrimonial;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MatrimonialProfileResource extends JsonResource
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
            'user_id' => $this->user_id,
            'family_member_id' => $this->family_member_id,
            'gender' => $this->gender,
            'age' => $this->age,
            'height' => $this->height,
            'weight' => $this->weight,
            'marital_status' => $this->marital_status,
            'education' => $this->education,
            'occupation' => $this->occupation,
            'annual_income' => $this->annual_income,
            'currency' => $this->currency,
            'religion' => $this->religion,
            'caste' => $this->caste,
            'sub_caste' => $this->sub_caste,
            'mother_tongue' => $this->mother_tongue,
            'country' => $this->country,
            'state' => $this->state,
            'city' => $this->city,
            'about_me' => $this->about_me,
            'partner_preferences' => $this->partner_preferences,
            'is_active' => $this->is_active,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'profile_images' => $this->getMedia('profile_images')->map(function ($media) {
                return [
                    'id' => $media->id,
                    'url' => $media->getUrl(),
                    'thumbnail_url' => $media->getUrl('thumb'),
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}