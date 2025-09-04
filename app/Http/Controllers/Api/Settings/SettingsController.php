<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;

class SettingsController extends Controller
{
    /**
     * Get payment gateway settings.
     *
     * @return JsonResponse
     */
    public function paymentGateway(): JsonResponse
    {
        $keyId = Setting::get('payment_gateway_key_id');
        $keySecret = Setting::get('payment_gateway_key_secret');

        return response()->json([
            'key_id' => $keyId,
            'key_secret' => $keySecret,
        ]);
    }
    
    /**
     * Get all settings.
     *
     * @return JsonResponse
     */
    public function all(): JsonResponse
    {
        $settings = Setting::all();
        
        $formattedSettings = [];
        foreach ($settings as $setting) {
            $formattedSettings[$setting->key] = $setting->value;
        }
        
        return response()->json($formattedSettings);
    }
}