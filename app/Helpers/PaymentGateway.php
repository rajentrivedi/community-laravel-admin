<?php

namespace App\Helpers;

use App\Models\Setting;

class PaymentGateway
{
    /**
     * Get the payment gateway key ID.
     *
     * @return string|null
     */
    public static function getKeyId()
    {
        return Setting::get('payment_gateway_key_id');
    }

    /**
     * Get the payment gateway key secret.
     *
     * @return string|null
     */
    public static function getKeySecret()
    {
        return Setting::get('payment_gateway_key_secret');
    }

    /**
     * Get all payment gateway settings as an array.
     *
     * @return array
     */
    public static function getSettings()
    {
        return [
            'key_id' => self::getKeyId(),
            'key_secret' => self::getKeySecret(),
        ];
    }
}