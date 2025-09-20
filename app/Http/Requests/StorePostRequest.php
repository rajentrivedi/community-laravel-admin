<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // We'll handle authorization via policies
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'published_at' => 'nullable|date',
            // 'community_id' => 'required|exists:communities,id',
            'type' => 'nullable|string|max:50',
            // 'is_pinned' => 'nullable|boolean',
            'images' => 'nullable|array|max:5',
            'images.*' => 'file|mimes:jpeg,png,jpg,gif,webp|max:2048', // 2MB max
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'A title is required',
            'body.required' => 'Content is required',
            'community_id.required' => 'A community must be selected',
            'community_id.exists' => 'The selected community does not exist',
            'images.*.mimes' => 'Only JPEG, PNG, JPG, GIF, and WEBP images are allowed',
            'images.*.max' => 'Images must not be larger than 2MB',
        ];
    }
}