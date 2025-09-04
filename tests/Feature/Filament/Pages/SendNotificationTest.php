<?php

namespace Tests\Feature\Filament\Pages;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SendNotificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_load_the_send_notification_page()
    {
        // Create a user with admin permissions
        $user = User::factory()->create();
        $user->assignRole('admin');

        // Acting as the admin user
        $this->actingAs($user);

        // Visit the send notification page
        $response = $this->get('/admin/send-notification');

        // Assert that the page loads successfully
        $response->assertStatus(200);
    }
}