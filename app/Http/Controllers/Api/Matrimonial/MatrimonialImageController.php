<?php

namespace App\Http\Controllers\Api\Matrimonial;

use App\Http\Controllers\Controller;
use App\Models\MatrimonialProfile;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MatrimonialImageController extends Controller
{
    /**
     * Upload images for a matrimonial profile.
     */
    public function uploadImages(Request $request, MatrimonialProfile $matrimonialProfile): JsonResponse
    {
        $request->validate([
            'images' => 'required|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        foreach ($request->file('images') as $image) {
            $matrimonialProfile->addMedia($image)->toMediaCollection('profile_images');
        }

        return response()->json([
            'message' => 'Images uploaded successfully',
            'images' => $matrimonialProfile->getMedia('profile_images')
        ]);
    }

    /**
     * Delete an image from a matrimonial profile.
     */
    public function deleteImage(MatrimonialProfile $matrimonialProfile, $mediaId): JsonResponse
    {
        $media = $matrimonialProfile->getMedia('profile_images')->where('id', $mediaId)->first();

        if (!$media) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        $media->delete();

        return response()->json(['message' => 'Image deleted successfully']);
    }
}