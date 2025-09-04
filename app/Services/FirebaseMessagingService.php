<?php

namespace App\Services;

use App\Models\User;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;

class FirebaseMessagingService
{
    /**
     * Send a notification to a specific device
     *
     * @param string $deviceToken
     * @param string $title
     * @param string $body
     * @param array $data
     * @return void
     */
  
    public function sendToDevice($deviceToken, $title, $body, $data = [])
    {
        $messaging = Firebase::messaging();

        $message = CloudMessage::withTarget('token', $deviceToken)
            ->withNotification(Notification::create($title, $body))
            ->withData($data);

        $messaging->send($message);
    }

    /**
     * Send a notification to a specific user
     *
     * @param User $user
     * @param string $title
     * @param string $body
     * @param array $data
     * @return void
     */
    public function sendToUser(User $user, $title, $body, $data = [])
    {
        $fcmTokenService = new FcmTokenService();
        $deviceTokens = $fcmTokenService->getNotificationTokens($user);
        
        if (!empty($deviceTokens)) {
            $this->sendToDevices($deviceTokens, $title, $body, $data);
        }
    }

    /**
     * Send a notification to a topic
     *
     * @param string $topic
     * @param string $title
     * @param string $body
     * @param array $data
     * @return void
     */
    public function sendToTopic($topic, $title, $body, $data = [])
    {
        $messaging = Firebase::messaging();

        $message = CloudMessage::withTarget('topic', $topic)
            ->withNotification(Notification::create($title, $body))
            ->withData($data);

        $messaging->send($message);
    }

    /**
     * Send a notification to multiple devices
     *
     * @param array $deviceTokens
     * @param string $title
     * @param string $body
     * @param array $data
     * @return void
     */
    public function sendToDevices($deviceTokens, $title, $body, $data = [])
    {
        try {
            $messaging = Firebase::messaging();

            $message = CloudMessage::new()
                ->withNotification(Notification::create($title, $body))
                ->withData($data);

            $messaging->sendMulticast($message, $deviceTokens);
        } catch (\Exception $e) {
            // Log the error
            \Log::error($e->getMessage());
        }
    }

    /**
     * Subscribe a device to a topic
     *
     * @param string $deviceToken
     * @param string $topic
     * @return void
     */
    public function subscribeToTopic($deviceToken, $topic)
    {
        $messaging = Firebase::messaging();
        $messaging->subscribeToTopic($topic, $deviceToken);
    }

    /**
     * Unsubscribe a device from a topic
     *
     * @param string $deviceToken
     * @param string $topic
     * @return void
     */
    public function unsubscribeFromTopic($deviceToken, $topic)
    {
        $messaging = Firebase::messaging();
        $messaging->unsubscribeFromTopic($topic, $deviceToken);
    }
}