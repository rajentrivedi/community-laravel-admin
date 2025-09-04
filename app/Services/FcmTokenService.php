<?php

namespace App\Services;

use App\Models\FcmToken;
use App\Models\User;

class FcmTokenService
{
    /**
     * Register or update a user's FCM token.
     *
     * @param User $user
     * @param string $deviceToken
     * @param string|null $deviceType
     * @param string|null $deviceName
     * @return FcmToken
     */
    public function registerToken(User $user, string $deviceToken, ?string $deviceType = null, ?string $deviceName = null): FcmToken
    {
        return FcmToken::updateOrCreate(
            [
                'user_id' => $user->id,
                'device_token' => $deviceToken,
            ],
            [
                'device_type' => $deviceType,
                'device_name' => $deviceName,
            ]
        );
    }

    /**
     * Remove a user's FCM token.
     *
     * @param User $user
     * @param string $deviceToken
     * @return bool
     */
    public function removeToken(User $user, string $deviceToken): bool
    {
        $fcmToken = FcmToken::where('user_id', $user->id)
            ->where('device_token', $deviceToken)
            ->first();

        if ($fcmToken) {
            return $fcmToken->delete();
        }

        return false;
    }

    /**
     * Get all FCM tokens for a user.
     *
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserTokens(User $user)
    {
        return $user->fcmTokens;
    }

    /**
     * Get a user's FCM tokens by device type.
     *
     * @param User $user
     * @param string $deviceType
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserTokensByType(User $user, string $deviceType)
    {
        return $user->fcmTokens()->where('device_type', $deviceType)->get();
    }

    /**
     * Get all FCM tokens for sending notifications.
     *
     * @param User $user
     * @return array
     */
    public function getNotificationTokens(User $user): array
    {
        return $user->fcmTokens()->pluck('device_token')->toArray();
    }
}