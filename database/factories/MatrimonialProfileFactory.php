<?php

namespace Database\Factories;

use App\Models\MatrimonialProfile;
use App\Models\User;
use App\Models\FamilyMember;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MatrimonialProfile>
 */
class MatrimonialProfileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MatrimonialProfile::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'family_member_id' => null,
            'age' => $this->faker->numberBetween(18, 60),
            'height' => $this->faker->numberBetween(150, 200),
            'weight' => $this->faker->numberBetween(45, 100),
            'marital_status' => $this->faker->randomElement(['single', 'divorced', 'widowed', 'separated']),
            'education' => $this->faker->randomElement([
                'Bachelor of Technology',
                'Master of Business Administration',
                'Bachelor of Arts',
                'Master of Arts',
                'Bachelor of Science',
                'Master of Science',
                'Doctor of Philosophy',
                'Bachelor of Commerce',
                'Master of Commerce',
                'Bachelor of Medicine',
                'Law Degree',
                'Diploma in Engineering'
            ]),
            'occupation' => $this->faker->randomElement([
                'Software Engineer',
                'Doctor',
                'Teacher',
                'Marketing Manager',
                'Business Analyst',
                'Accountant',
                'Lawyer',
                'Architect',
                'Graphic Designer',
                'Civil Engineer',
                'Data Scientist',
                'HR Manager',
                'Banker',
                'Entrepreneur',
                'Consultant',
                'Professor',
                'Journalist',
                'Chef',
                'Artist'
            ]),
            'annual_income' => $this->faker->randomFloat(2, 200000, 5000000),
            'currency' => 'INR',
            'religion' => $this->faker->randomElement([
                'Hindu',
                'Muslim',
                'Christian',
                'Sikh',
                'Buddhist',
                'Jain',
                'Other'
            ]),
            'caste' => $this->faker->randomElement([
                'Brahmin',
                'Kshatriya',
                'Vaishya',
                'Shudra',
                'OBC',
                'SC',
                'ST',
                'Other'
            ]),
            'sub_caste' => $this->faker->randomElement([
                'Gaur',
                'Kanyakubja',
                'Bhoi',
                'Rajput',
                'Jat',
                'Maratha',
                'Agarwal',
                'Khatri',
                'Bania',
                'Yadav',
                'Ahir',
                'Kurmi',
                'Patel',
                'Lingayat',
                'Reddy',
                'Nair',
                'Pillai',
                'Mukkulathor',
                ''
            ]),
            'mother_tongue' => $this->faker->randomElement([
                'Hindi',
                'English',
                'Bengali',
                'Telugu',
                'Marathi',
                'Tamil',
                'Urdu',
                'Gujarati',
                'Kannada',
                'Odia',
                'Punjabi',
                'Malayalam',
                'Assamese',
                'Kashmiri'
            ]),
            'country' => 'India',
            'state' => $this->faker->randomElement([
                'Maharashtra',
                'Delhi',
                'Karnataka',
                'Tamil Nadu',
                'West Bengal',
                'Gujarat',
                'Uttar Pradesh',
                'Punjab',
                'Rajasthan',
                'Madhya Pradesh',
                'Andhra Pradesh',
                'Telangana',
                'Kerala',
                'Odisha',
                'Chhattisgarh',
                'Jharkhand',
                'Bihar',
                'Haryana',
                'Assam',
                'Puducherry'
            ]),
            'city' => $this->faker->randomElement([
                'Mumbai',
                'Delhi',
                'Bangalore',
                'Chennai',
                'Kolkata',
                'Ahmedabad',
                'Hyderabad',
                'Pune',
                'Surat',
                'Jaipur',
                'Lucknow',
                'Kanpur',
                'Nagpur',
                'Indore',
                'Thane',
                'Bhopal',
                'Visakhapatnam',
                'Pimpri-Chinchwad',
                'Patna',
                'Vadodara',
                'Ghaziabad',
                'Ludhiana',
                'Agra',
                'Nashik',
                'Faridabad',
                'Meerut',
                'Rajkot',
                'Kalyan-Dombivli',
                'Vasai-Virar',
                'Varanasi'
            ]),
            'about_me' => $this->faker->paragraph(3),
            'partner_preferences' => $this->faker->paragraph(2),
            'is_active' => true,
        ];
    }
}