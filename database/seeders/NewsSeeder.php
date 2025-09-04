<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\User;
use App\Models\Community;
use Illuminate\Support\Str;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing users and communities
        $users = User::all();
        $communities = Community::all();
        
        // If we don't have enough users or communities, create some
        if ($users->count() < 10) {
            User::factory(10 - $users->count())->create();
            $users = User::all();
        }
        
        if ($communities->count() < 5) {
            Community::factory(5 - $communities->count())->create();
            $communities = Community::all();
        }
        
        // Create 100 news items with dummy data
        $newsItems = News::factory()
            ->count(100)
            ->state(function () use ($users, $communities) {
                return [
                    'user_id' => $users->random()->id,
                    'community_id' => $communities->random()->id,
                ];
            })
            ->create();
        
        // Add placeholder images to each news item
        foreach ($newsItems as $news) {
            // Create a simple PNG placeholder image
            $image = imagecreate(800, 600);
            $bgColor = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
            $textColor = imagecolorallocate($image, 255, 255, 255);
            
            // Add background color text
            imagestring($image, 5, 300, 280, 'News Image', $textColor);
            imagestring($image, 3, 350, 320, 'ID: ' . $news->id, $textColor);
            
            // Save PNG to temporary file
            $tempFile = tempnam(sys_get_temp_dir(), 'news_image') . '.png';
            imagepng($image, $tempFile);
            imagedestroy($image);
            
            // Add the image to the news item
            $news->addMedia($tempFile)
                ->toMediaCollection('images');
            
            // Clean up temporary file if it exists
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
        }
    }
}