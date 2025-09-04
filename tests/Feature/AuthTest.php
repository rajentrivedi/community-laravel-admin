<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('user can login with correct credentials', function () {
    $user = User::factory()->create([
        'email' => 'login-test1@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'login-test1@example.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'user' => [
                'id',
                'name',
                'email',
                'status',
                'created_at',
                'updated_at',
            ],
            'token',
        ]);

    $this->assertDatabaseHas('personal_access_tokens', [
        'tokenable_id' => $user->id,
        'tokenable_type' => User::class,
    ]);
});

test('user cannot login with incorrect credentials', function () {
    $user = User::factory()->create([
        'email' => 'login-test2@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'login-test2@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(422);
});

test('user can logout with valid token', function () {
    $user = User::factory()->create();
    
    // Authenticate using Sanctum by creating a token
    $token = $user->createToken('test-token')->plainTextToken;
    
    // Use the token in the Authorization header
    $response = $this->withHeader('Authorization', "Bearer $token")
        ->postJson('/api/logout');
    
    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Logged out successfully',
        ]);
        
    // After logout, the token should be deleted
    $this->assertDatabaseMissing('personal_access_tokens', [
        'tokenable_id' => $user->id,
        'tokenable_type' => User::class,
    ]);
});
