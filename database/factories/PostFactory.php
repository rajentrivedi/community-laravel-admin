<?php

namespace Database\Factories;

use App\Models\Community;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence();
        
        return [
            'community_id' => Community::inRandomOrder()->first()?->id ?? Community::factory(),
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'type' => $this->faker->randomElement(['news', 'announcement', 'discussion']),
            'title' => $title,
            'body' => $this->faker->paragraphs(3, true),
            'published_at' => $this->faker->boolean(70) ? $this->faker->dateTimeBetween('-1 year', 'now') : null, // 70% of posts are published
            'is_pinned' => $this->faker->boolean(5), // 5% of posts are pinned
            'created_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
    
    /**
     * Mark the post as published
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ]);
    }
    
    /**
     * Mark the post as draft (not published)
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'published_at' => null,
        ]);
    }
    
    /**
     * Mark the post as pinned
     */
    public function pinned(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_pinned' => true,
        ]);
    }
}
