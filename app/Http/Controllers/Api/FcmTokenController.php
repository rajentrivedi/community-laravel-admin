<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FcmTokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FcmTokenController extends Controller
{
    protected $fcmTokenService;

    public function __construct(FcmTokenService $fcmTokenService)
    {
        $this->fcmTokenService = $fcmTokenService;
    }

    /**
     * Register or update a user's FCM token.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerToken(Request $request)
    {
        $request->validate([
            'device_token' => 'required|string',
            'device_type' => 'nullable|string|in:android,ios,web',
            'device_name' => 'nullable|string|max:255',
        ]);

        try {
            // Get the authenticated user
            $user = Auth::user();
            
            // Register the FCM token for this user
            $fcmToken = $this->fcmTokenService->registerToken(
                $user,
                $request->device_token,
                $request->device_type,
                $request->device_name
            );

            return response()->json([
                'success' => true,
                'message' => 'FCM token registered successfully',
                'data' => $fcmToken
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to register FCM token: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove a user's FCM token.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeToken(Request $request)
    {
        $request->validate([
            'device_token' => 'required|string',
        ]);

        try {
            // Get the authenticated user
            $user = Auth::user();
            
            // Remove the FCM token
            $result = $this->fcmTokenService->removeToken($user, $request->device_token);
                
            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'FCM token removed successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'FCM token not found'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove FCM token: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all FCM tokens for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserTokens()
    {
        try {
            $user = Auth::user();
            
            $tokens = $this->fcmTokenService->getUserTokens($user);
            
            return response()->json([
                'success' => true,
                'data' => $tokens
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve FCM tokens: ' . $e->getMessage()
            ], 500);
        }
    }
}