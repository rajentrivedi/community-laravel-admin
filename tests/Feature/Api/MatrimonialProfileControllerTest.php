<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\MatrimonialProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MatrimonialProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_matrimonial_profiles()
    {
        // Create a user and matrimonial profile
        $user = User::factory()->create();
        $profile = MatrimonialProfile::factory()->create([
            'user_id' => $user->id,
            'is_active' => true
        ]);

        // Make a request to the index endpoint
        $response = $this->getJson('/api/matrimonial-profiles');

        // Assert the response is successful
        $response->assertStatus(200);

        // Assert the profile is in the response
        $response->assertJsonFragment([
            'id' => $profile->id
        ]);
    }

    /** @test */
    public function it_can_list_matrimonial_profiles_by_gender()
    {
        // Create a user and matrimonial profile
        $user = User::factory()->create();
        $profile = MatrimonialProfile::factory()->create([
            'user_id' => $user->id,
            'gender' => 'male',
            'is_active' => true
        ]);

        // Make a request to the byGender endpoint
        $response = $this->getJson('/api/matrimonial-profiles/by-gender/male');

        // Assert the response is successful
        $response->assertStatus(200);

        // Assert the profile is in the response
        $response->assertJsonFragment([
            'id' => $profile->id
        ]);
    }
}