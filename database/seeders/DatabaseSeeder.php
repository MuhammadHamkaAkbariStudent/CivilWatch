<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Model::unguard(); // Allow manual timestamp assignment during seeding

        $this->call([
            UserSeeder::class,           // 1. No foreign key
            DistrictSeeder::class,       // 2. No foreign key
            ReportSeeder::class,         // 3. Requires users & districts
            ProgressUpdateSeeder::class, // 4. Requires reports
            UpvoteSeeder::class,         // 5. Requires users & reports
        ]);

        Model::reguard(); // Restore mass assignment protection after seeding
    }
}