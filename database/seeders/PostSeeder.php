<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 100 posts
        Post::factory(100)->create();
        
        // Log the seeding completion
        Log::info('Seeded 100 posts');
    }
}