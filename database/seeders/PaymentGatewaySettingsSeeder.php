<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentGatewaySettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create initial payment gateway settings
        Setting::updateOrCreate(
            ['key' => 'payment_gateway_key_id'],
            [
                'value' => null,
                'group' => 'payment',
            ]
        );

        Setting::updateOrCreate(
            ['key' => 'payment_gateway_key_secret'],
            [
                'value' => null,
                'group' => 'payment',
            ]
        );
    }
}
