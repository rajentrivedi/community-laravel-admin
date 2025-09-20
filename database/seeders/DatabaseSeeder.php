<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PaymentGatewaySettingsSeeder::class,
            CommunitySeeder::class,
            NewsSeeder::class,
            MatrimonialProfileSeeder::class,
            PostSeeder::class,
        ]);

        User::factory(1000)->create();
    }
}
