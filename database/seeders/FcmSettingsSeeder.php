<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\SettingsService;
use Illuminate\Console\Command;

class FcmSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Command $command = null): void
    {
        $settingsService = new SettingsService();
        
        // Path to the google-services.json file
        $googleServicesPath = storage_path('app/google-services.json');
        
        // Check if the file exists
        if (file_exists($googleServicesPath)) {
            // Read the JSON file
            $googleServices = json_decode(file_get_contents($googleServicesPath), true);
            
            // Extract the necessary values
            $projectId = $googleServices['project_info']['project_id'] ?? null;
            $storageBucket = $googleServices['project_info']['storage_bucket'] ?? null;
            $apiKey = null;
            $messagingSenderId = $googleServices['project_info']['project_number'] ?? null;
            $appId = null;
            
            // Extract API key from client configuration
            if (isset($googleServices['client']) && !empty($googleServices['client'])) {
                $client = $googleServices['client'][0];
                if (isset($client['api_key']) && !empty($client['api_key'])) {
                    $apiKey = $client['api_key'][0]['current_key'] ?? null;
                }
                
                if (isset($client['client_info']['mobilesdk_app_id'])) {
                    $appId = $client['client_info']['mobilesdk_app_id'];
                }
            }
            
            // Set the Firebase settings
            $firebaseSettings = [
                'project_id' => $projectId,
                'api_key' => $apiKey,
                'messaging_sender_id' => $messagingSenderId,
                'app_id' => $appId,
                'storage_bucket' => $storageBucket,
            ];
            
            // Save to settings table
            $settingsService->setFirebaseSettings($firebaseSettings);
            
            if ($command) {
                $command->info('FCM settings have been successfully seeded.');
            }
        } else {
            if ($command) {
                $command->error('google-services.json file not found at: ' . $googleServicesPath);
            }
        }
    }
}