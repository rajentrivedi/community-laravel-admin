<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\FcmSettingsSeeder;

class SeedFcmSettingsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fcm:seed-settings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed Firebase Cloud Messaging settings from google-services.json';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $seeder = new FcmSettingsSeeder();
        $seeder->run();
        
        $this->info('FCM settings seeded successfully!');
    }
}