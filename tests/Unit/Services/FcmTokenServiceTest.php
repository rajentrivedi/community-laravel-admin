<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\User;
use App\Models\FcmToken;
use App\Services\FcmTokenService;

class FcmTokenServiceTest extends TestCase
{
    /** @test */
    public function it_can_register_a_new_fcm_token_for_a_user()
    {
        // Arrange
        $user = User::factory()->create();
        $fcmTokenService = new FcmTokenService();
        $deviceToken = 'test_device_token_123';
        $deviceType = 'android';
        $deviceName = 'Test Device';

        // Act
        $fcmToken = $fcmTokenService->registerToken($user, $deviceToken, $deviceType, $deviceName);

        // Assert
        $this->assertDatabaseHas('fcm_tokens', [
            'user_id' => $user->id,
            'device_token' => $deviceToken,
            'device_type' => $deviceType,
            'device_name' => $deviceName,
        ]);

        $this->assertEquals($user->id, $fcmToken->user_id);
        $this->assertEquals($deviceToken, $fcmToken->device_token);
        $this->assertEquals($deviceType, $fcmToken->device_type);
        $this->assertEquals($deviceName, $fcmToken->device_name);
    }

    /** @test */
    public function it_can_update_an_existing_fcm_token_for_a_user()
    {
        // Arrange
        $user = User::factory()->create();
        $fcmTokenService = new FcmTokenService();
        $deviceToken = 'test_device_token_123';
        
        // Create initial token
        $fcmTokenService->registerToken($user, $deviceToken, 'android', 'Old Device');
        
        // Act - Update with new information
        $updatedToken = $fcmTokenService->registerToken($user, $deviceToken, 'ios', 'New Device');

        // Assert
        $this->assertDatabaseHas('fcm_tokens', [
            'user_id' => $user->id,
            'device_token' => $deviceToken,
            'device_type' => 'ios',
            'device_name' => 'New Device',
        ]);

        $this->assertEquals(1, FcmToken::count()); // Should still only have one record
        $this->assertEquals('ios', $updatedToken->device_type);
        $this->assertEquals('New Device', $updatedToken->device_name);
    }

    /** @test */
    public function it_can_remove_an_fcm_token_for_a_user()
    {
        // Arrange
        $user = User::factory()->create();
        $fcmTokenService = new FcmTokenService();
        $deviceToken = 'test_device_token_123';
        
        // Create a token
        $fcmTokenService->registerToken($user, $deviceToken);
        
        // Act
        $result = $fcmTokenService->removeToken($user, $deviceToken);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('fcm_tokens', [
            'user_id' => $user->id,
            'device_token' => $deviceToken,
        ]);
    }

    /** @test */
    public function it_returns_false_when_trying_to_remove_nonexistent_token()
    {
        // Arrange
        $user = User::factory()->create();
        $fcmTokenService = new FcmTokenService();
        $deviceToken = 'nonexistent_token_123';

        // Act
        $result = $fcmTokenService->removeToken($user, $deviceToken);

        // Assert
        $this->assertFalse($result);
    }

    /** @test */
    public function it_can_get_all_fcm_tokens_for_a_user()
    {
        // Arrange
        $user = User::factory()->create();
        $fcmTokenService = new FcmTokenService();
        
        // Create multiple tokens
        $fcmTokenService->registerToken($user, 'token_1', 'android', 'Device 1');
        $fcmTokenService->registerToken($user, 'token_2', 'ios', 'Device 2');
        
        // Act
        $tokens = $fcmTokenService->getUserTokens($user);

        // Assert
        $this->assertCount(2, $tokens);
        $this->assertEquals('token_1', $tokens[0]->device_token);
        $this->assertEquals('token_2', $tokens[1]->device_token);
    }

    /** @test */
    public function it_can_get_notification_tokens_for_a_user()
    {
        // Arrange
        $user = User::factory()->create();
        $fcmTokenService = new FcmTokenService();
        
        // Create multiple tokens
        $fcmTokenService->registerToken($user, 'token_1', 'android', 'Device 1');
        $fcmTokenService->registerToken($user, 'token_2', 'ios', 'Device 2');
        
        // Act
        $tokens = $fcmTokenService->getNotificationTokens($user);

        // Assert
        $this->assertCount(2, $tokens);
        $this->assertContains('token_1', $tokens);
        $this->assertContains('token_2', $tokens);
    }
}