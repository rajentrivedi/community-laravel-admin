<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Database\Seeders\FcmSettingsSeeder;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('fcm:seed-settings', function () {
    $seeder = new FcmSettingsSeeder();
    $seeder->run($this);
    
    $this->info('FCM settings seeded successfully!');
})->purpose('Seed Firebase Cloud Messaging settings from google-services.json');
