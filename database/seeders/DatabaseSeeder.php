<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CitySeeder::class,
            GymSeeder::class,
            WorkoutCategorySeeder::class,
            UserSeeder::class,
            WorkoutIntentSeeder::class,
            WorkoutRequestSeeder::class,
        ]);
    }
}
