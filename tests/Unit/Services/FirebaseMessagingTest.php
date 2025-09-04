<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\FirebaseMessagingService;
use App\Services\SettingsService;
use Database\Seeders\FcmSettingsSeeder;

class FirebaseMessagingTest extends TestCase
{
    /** @test */
    public function it_can_extract_fcm_settings_from_google_services_json()
    {
        // Arrange
        $seeder = new FcmSettingsSeeder();
        
        // Act
        $seeder->run();
        
        // Assert
        $settingsService = new SettingsService();
        $firebaseSettings = $settingsService->getFirebaseSettings();
        
        $this->assertArrayHasKey('project_id', $firebaseSettings);
        $this->assertArrayHasKey('api_key', $firebaseSettings);
        $this->assertArrayHasKey('messaging_sender_id', $firebaseSettings);
        $this->assertArrayHasKey('app_id', $firebaseSettings);
        $this->assertArrayHasKey('storage_bucket', $firebaseSettings);
        
        // Check that the values are correctly extracted from the JSON file
        $this->assertEquals('community-notification', $firebaseSettings['project_id']);
        $this->assertEquals('AIzaSyA2PQtNVfnNyxpETeY9UKN7xE4l9o0My04', $firebaseSettings['api_key']);
        $this->assertEquals('929352446579', $firebaseSettings['messaging_sender_id']);
        $this->assertEquals('1:929352446579:android:7af1b1ede37b035b21f006', $firebaseSettings['app_id']);
        $this->assertEquals('community-notification.firebasestorage.app', $firebaseSettings['storage_bucket']);
    }
}