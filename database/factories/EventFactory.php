<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Community;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get random start date within the next 30 days
        $startAt = $this->faker->dateTimeBetween('now', '+30 days');
        
        // End date should be after start date, within 5 hours
        $endAt = $this->faker->dateTimeBetween(
            $startAt->format('Y-m-d H:i:s'),
            $startAt->modify('+5 hours')->format('Y-m-d H:i:s')
        );
        
        // Get a random community and user
        $community = Community::inRandomOrder()->first();
        $user = User::inRandomOrder()->first();

        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'venue_name' => $this->faker->company(),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'pincode' => $this->faker->postcode(),
            'lat' => $this->faker->latitude(),
            'lng' => $this->faker->longitude(),
            'start_at' => $startAt,
            'end_at' => $endAt,
            'community_id' => $community->id,
            'user_id' => $user->id,
            'status' => $this->faker->randomElement(['active', 'cancelled', 'completed']),
            'max_attendees' => $this->faker->numberBetween(10, 100),
        ];
    }
}
