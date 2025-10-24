<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed amenities first (required for accommodations)
        $this->call([
            AmenitySeeder::class,
            TestDataSeeder::class,
        ]);
    }
}
