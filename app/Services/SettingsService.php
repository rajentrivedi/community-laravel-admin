<?php

namespace App\Services;

use App\Models\Setting;

class SettingsService
{
    /**
     * Get a setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return Setting::get($key, $default);
    }

    /**
     * Set a setting value by key.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set($key, $value)
    {
        Setting::set($key, $value);
    }

    /**
     * Get payment gateway settings.
     *
     * @return array
     */
    public function getPaymentGatewaySettings()
    {
        return [
            'key_id' => $this->get('payment_gateway_key_id'),
            'key_secret' => $this->get('payment_gateway_key_secret'),
        ];
    }

    /**
     * Set payment gateway settings.
     *
     * @param array $settings
     * @return void
     */
    public function setPaymentGatewaySettings($settings)
    {
        if (isset($settings['key_id'])) {
            $this->set('payment_gateway_key_id', $settings['key_id']);
        }

        if (isset($settings['key_secret'])) {
            $this->set('payment_gateway_key_secret', $settings['key_secret']);
        }
    }

    /**
     * Get Firebase settings.
     *
     * @return array
     */
    public function getFirebaseSettings()
    {
        return [
            'project_id' => $this->get('firebase_project_id'),
            'api_key' => $this->get('firebase_api_key'),
            'messaging_sender_id' => $this->get('firebase_messaging_sender_id'),
            'app_id' => $this->get('firebase_app_id'),
            'storage_bucket' => $this->get('firebase_storage_bucket'),
        ];
    }

    /**
     * Set Firebase settings.
     *
     * @param array $settings
     * @return void
     */
    public function setFirebaseSettings($settings)
    {
        if (isset($settings['project_id'])) {
            $this->set('firebase_project_id', $settings['project_id']);
        }

        if (isset($settings['api_key'])) {
            $this->set('firebase_api_key', $settings['api_key']);
        }

        if (isset($settings['messaging_sender_id'])) {
            $this->set('firebase_messaging_sender_id', $settings['messaging_sender_id']);
        }

        if (isset($settings['app_id'])) {
            $this->set('firebase_app_id', $settings['app_id']);
        }

        if (isset($settings['storage_bucket'])) {
            $this->set('firebase_storage_bucket', $settings['storage_bucket']);
        }
    }
}