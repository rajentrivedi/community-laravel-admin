<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\FirebaseMessagingService;
use Illuminate\Http\Request;

class FirebaseMessagingController extends Controller
{
    protected $firebaseMessagingService;

    public function __construct(FirebaseMessagingService $firebaseMessagingService)
    {
        $this->firebaseMessagingService = $firebaseMessagingService;
    }

    /**
     * Send a notification to a specific device
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendToDevice(Request $request)
    {
        $request->validate([
            'device_token' => 'required|string',
            'title' => 'required|string',
            'body' => 'required|string',
            'data' => 'nullable|array',
        ]);

        try {
            $this->firebaseMessagingService->sendToDevice(
                $request->device_token,
                $request->title,
                $request->body,
                $request->data ?? []
            );

            return response()->json([
                'success' => true,
                'message' => 'Notification sent successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send notification: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send a notification to a topic
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendToTopic(Request $request)
    {
        $request->validate([
            'topic' => 'required|string',
            'title' => 'required|string',
            'body' => 'required|string',
            'data' => 'nullable|array',
        ]);

        try {
            $this->firebaseMessagingService->sendToTopic(
                $request->topic,
                $request->title,
                $request->body,
                $request->data ?? []
            );

            return response()->json([
                'success' => true,
                'message' => 'Notification sent to topic successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send notification to topic: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Subscribe a device to a topic
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function subscribeToTopic(Request $request)
    {
        $request->validate([
            'device_token' => 'required|string',
            'topic' => 'required|string',
        ]);

        try {
            $this->firebaseMessagingService->subscribeToTopic(
                $request->device_token,
                $request->topic
            );

            return response()->json([
                'success' => true,
                'message' => 'Device subscribed to topic successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to subscribe device to topic: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Unsubscribe a device from a topic
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unsubscribeFromTopic(Request $request)
    {
        $request->validate([
            'device_token' => 'required|string',
            'topic' => 'required|string',
        ]);

        try {
            $this->firebaseMessagingService->unsubscribeFromTopic(
                $request->device_token,
                $request->topic
            );

            return response()->json([
                'success' => true,
                'message' => 'Device unsubscribed from topic successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to unsubscribe device from topic: ' . $e->getMessage()
            ], 500);
        }
    }
}