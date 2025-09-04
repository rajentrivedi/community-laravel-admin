<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    protected FirebaseMessagingService $firebaseMessaging;

    public function __construct(FirebaseMessagingService $firebaseMessaging)
    {
        $this->firebaseMessaging = $firebaseMessaging;
    }

    /**
     * Send a notification through FCM
     *
     * @param Notification $notification
     * @return bool
     */
    public function sendFcmNotification(Notification $notification): bool
    {
        try {
            $data = $notification->data ?? [];
            
            switch ($notification->target_type) {
                case 'all_users':
                    // For all users, we'll send to a predefined topic
                    $this->firebaseMessaging->sendToTopic('all_users', $notification->title, $notification->body, $data);
                    break;
                    
                case 'specific_user':
                    // Get the user by ID
                    $user = User::find($notification->target_value);
                    if ($user) {
                        $this->firebaseMessaging->sendToUser($user, $notification->title, $notification->body, $data);
                        return true;
                    }
                    return false;
                    
                case 'topic':
                    $this->firebaseMessaging->sendToTopic($notification->target_value, $notification->title, $notification->body, $data);
                    break;
                    
                default:
                    return false;
            }
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send FCM notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send a notification to all users
     *
     * @param string $title
     * @param string $body
     * @param array $data
     * @return bool
     */
    public function sendToAllUsers(string $title, string $body, array $data = []): bool
    {
        try {
            $this->firebaseMessaging->sendToTopic('all_users', $title, $body, $data);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send notification to all users: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send a notification to a specific user
     *
     * @param User $user
     * @param string $title
     * @param string $body
     * @param array $data
     * @return bool
     */
    public function sendToUser(User $user, string $title, string $body, array $data = []): bool
    {
        try {
            $this->firebaseMessaging->sendToUser($user, $title, $body, $data);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send notification to user: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send a notification to a topic
     *
     * @param string $topic
     * @param string $title
     * @param string $body
     * @param array $data
     * @return bool
     */
    public function sendToTopic(string $topic, string $title, string $body, array $data = []): bool
    {
        try {
            $this->firebaseMessaging->sendToTopic($topic, $title, $body, $data);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send notification to topic: ' . $e->getMessage());
            return false;
        }
    }
}