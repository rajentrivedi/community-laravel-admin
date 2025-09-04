<?php

namespace Database\Seeders;

use App\Models\MatrimonialProfile;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MatrimonialProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing users
        $users = User::all();
        
        // If we don't have enough users, create some
        if ($users->count() < 200) {
            User::factory(200 - $users->count())->create();
            $users = User::all();
        }
        
        // Create 100 matrimonial profiles
        $profiles = MatrimonialProfile::factory()
            ->count(100)
            ->state(function (array $attributes) use ($users) {
                return [
                    'user_id' => $users->random()->id,
                ];
            })
            ->create();
        
        // Add multiple images to each profile (between 2 and 5 images per profile)
        foreach ($profiles as $profile) {
            $imageCount = rand(2, 5);
            for ($i = 0; $i < $imageCount; $i++) {
                // Create a simple PNG placeholder image
                $image = imagecreate(600, 800);
                $bgColor = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
                $textColor = imagecolorallocate($image, 255, 255, 255);
                
                // Add background color text
                imagestring($image, 5, 200, 380, 'Profile Image', $textColor);
                imagestring($image, 3, 250, 420, 'ID: ' . $profile->id, $textColor);
                imagestring($image, 3, 250, 450, 'Img: ' . ($i + 1), $textColor);
                
                // Save PNG to temporary file
                $tempFile = tempnam(sys_get_temp_dir(), 'matrimonial_profile_image') . '.png';
                imagepng($image, $tempFile);
                imagedestroy($image);
                
                // Add the image to the profile
                $profile->addMedia($tempFile)
                    ->toMediaCollection('profile_images');
                
                // Clean up temporary file if it exists
                if (file_exists($tempFile)) {
                    unlink($tempFile);
                }
            }
        }
    }
}