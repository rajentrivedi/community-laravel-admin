<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\FcmToken;
use Laravel\Sanctum\Sanctum;

class FcmTokenControllerTest extends TestCase
{
    /** @test */
    public function it_can_register_a_new_fcm_token_for_authenticated_user()
    {
        // Arrange
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        
        $requestData = [
            'device_token' => 'test_device_token_123',
            'device_type' => 'android',
            'device_name' => 'Test Device',
        ];

        // Act
        $response = $this->postJson('/api/fcm-tokens/register', $requestData);

        // Assert
        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'FCM token registered successfully',
            ]);
            
        $this->assertDatabaseHas('fcm_tokens', [
            'user_id' => $user->id,
            'device_token' => 'test_device_token_123',
            'device_type' => 'android',
            'device_name' => 'Test Device',
        ]);
    }

    /** @test */
    public function it_cannot_register_fcm_token_without_authentication()
    {
        // Arrange
        $requestData = [
            'device_token' => 'test_device_token_123',
        ];

        // Act
        $response = $this->postJson('/api/fcm-tokens/register', $requestData);

        // Assert
        $response->assertStatus(401);
    }

    /** @test */
    public function it_validates_required_fields_when_registering_fcm_token()
    {
        // Arrange
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Act
        $response = $this->postJson('/api/fcm-tokens/register', []);

        // Assert
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['device_token']);
    }

    /** @test */
    public function it_can_remove_an_fcm_token_for_authenticated_user()
    {
        // Arrange
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        
        // Create a token
        $fcmToken = FcmToken::create([
            'user_id' => $user->id,
            'device_token' => 'test_device_token_123',
        ]);
        
        $requestData = [
            'device_token' => 'test_device_token_123',
        ];

        // Act
        $response = $this->postJson('/api/fcm-tokens/remove', $requestData);

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'FCM token removed successfully',
            ]);
            
        $this->assertDatabaseMissing('fcm_tokens', [
            'id' => $fcmToken->id,
        ]);
    }

    /** @test */
    public function it_cannot_remove_nonexistent_fcm_token()
    {
        // Arrange
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        
        $requestData = [
            'device_token' => 'nonexistent_token_123',
        ];

        // Act
        $response = $this->postJson('/api/fcm-tokens/remove', $requestData);

        // Assert
        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'FCM token not found',
            ]);
    }

    /** @test */
    public function it_can_get_all_fcm_tokens_for_authenticated_user()
    {
        // Arrange
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        
        // Create multiple tokens
        FcmToken::create([
            'user_id' => $user->id,
            'device_token' => 'token_1',
            'device_type' => 'android',
        ]);
        
        FcmToken::create([
            'user_id' => $user->id,
            'device_token' => 'token_2',
            'device_type' => 'ios',
        ]);

        // Act
        $response = $this->getJson('/api/fcm-tokens/user');

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    [
                        'device_token' => 'token_1',
                        'device_type' => 'android',
                    ],
                    [
                        'device_token' => 'token_2',
                        'device_type' => 'ios',
                    ],
                ],
            ]);
    }
}