<?php

namespace Database\Factories;

use App\Models\FamilyMember;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FamilyMember>
 */
class FamilyMemberFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FamilyMember::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->name(),
            'relationship' => $this->faker->randomElement(['father', 'mother', 'spouse', 'child', 'sibling', 'other']),
            'date_of_birth' => $this->faker->date(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'organization' => $this->faker->company(),
            'job_title' => $this->faker->jobTitle(),
            'industry' => $this->faker->randomElement(['IT', 'Finance', 'Education', 'Healthcare', 'Manufacturing']),
            'employment_type' => $this->faker->randomElement(['full_time', 'part_time', 'self_employed', 'business', 'govt', 'psu', 'other']),
            'start_date_employment' => $this->faker->date(),
            'end_date_employment' => $this->faker->date(),
            'is_current_employment' => $this->faker->boolean(),
            'annual_income' => $this->faker->randomFloat(2, 100000, 10000000),
            'currency' => 'INR',
            'city_employment' => $this->faker->city(),
            'state_employment' => $this->faker->state(),
            'country_employment' => 'India',
            'degree_level' => $this->faker->randomElement(['10th', '12th', 'diploma', 'bachelors', 'masters', 'phd', 'other']),
            'field_of_study' => $this->faker->randomElement(['Computer Science', 'Commerce', 'Arts', 'Science', 'Engineering']),
            'institution' => $this->faker->company(),
            'board_university' => $this->faker->randomElement(['CBSE', 'GSEB', 'GTU', 'University of Mumbai', 'Delhi University']),
            'start_date_study' => $this->faker->date(),
            'end_date_study' => $this->faker->date(),
            'currently_studying' => $this->faker->boolean(),
            'grade' => $this->faker->randomElement(['A', 'B', 'C', 'D', '8.5 CGPA', '92%']),
            'city_study' => $this->faker->city(),
            'state_study' => $this->faker->state(),
            'country_study' => 'India',
        ];
    }
}