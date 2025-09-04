<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user list endpoint.
     *
     * @return void
     */
    public function test_can_get_users_list()
    {
        // Create test users
        $users = User::factory()->count(5)->create();

        // Make request to the API
        $response = $this->actingAs($users->first())->get('/api/users');

        // Assert response status
        $response->assertStatus(200);

        // Assert response structure
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'email', 'mobile_no', 'status', 'email_verified_at', 'created_at', 'updated_at']
            ],
            'links' => ['first', 'last', 'prev', 'next'],
            'meta' => ['current_page', 'from', 'last_page', 'path', 'per_page', 'to', 'total']
        ]);

        // Assert we have 5 users
        $this->assertCount(5, $response->json('data'));
    }

    /**
     * Test user detail endpoint.
     *
     * @return void
     */
    public function test_can_get_user_detail()
    {
        // Create a test user
        $user = User::factory()->create();

        // Make request to the API
        $response = $this->actingAs($user)->get("/api/users/{$user->id}");

        // Assert response status
        $response->assertStatus(200);

        // Assert response structure
        $response->assertJsonStructure([
            'data' => ['id', 'name', 'email', 'mobile_no', 'status', 'email_verified_at', 'created_at', 'updated_at']
        ]);

        // Assert response data matches user
        $response->assertJson([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ]
        ]);
    }

    /**
     * Test user detail endpoint with invalid ID.
     *
     * @return void
     */
    public function test_cannot_get_user_with_invalid_id()
    {
        // Create a test user to authenticate
        $user = User::factory()->create();

        // Make request to the API with invalid ID
        $response = $this->actingAs($user)->get('/api/users/999999');

        // Assert response status
        $response->assertStatus(404);
    }

    /**
     * Test user search endpoint.
     *
     * @return void
     */
    public function test_can_search_users()
    {
        // Create test users
        $user1 = User::factory()->create(['name' => 'John Doe', 'email' => 'john@example.com', 'mobile_no' => '1234567890']);
        $user2 = User::factory()->create(['name' => 'Jane Smith', 'email' => 'jane@example.com', 'mobile_no' => '0987654321']);
        $user3 = User::factory()->create(['name' => 'Bob Johnson', 'email' => 'bob@example.com', 'mobile_no' => '5555555555']);
        
        // Authenticate as one of the users
        $this->actingAs($user1);

        // Test search by name
        $response = $this->get('/api/users/search?query=John');
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.name', 'John Doe');

        // Test search by email
        $response = $this->get('/api/users/search?query=jane@example.com');
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.email', 'jane@example.com');

        // Test search by mobile number
        $response = $this->get('/api/users/search?query=5555555555');
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.mobile_no', '5555555555');

        // Test search with no results
        $response = $this->get('/api/users/search?query=nonexistent');
        $response->assertStatus(200);
        $response->assertJsonCount(0, 'data');
    }

    /**
     * Test user search endpoint with missing query parameter.
     *
     * @return void
     */
    public function test_cannot_search_users_without_query()
    {
        // Create a test user
        $user = User::factory()->create();

        // Make request to the API without query parameter
        $response = $this->actingAs($user)->get('/api/users/search');

        // Assert response status
        $response->assertStatus(400);
    }
}
