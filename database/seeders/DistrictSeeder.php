<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\District;

class DistrictSeeder extends Seeder
{
    public function run(): void
    {
        $districts = [
            'Jekan Raya',
            'Pahandut',
            'Sabangau',
            'Bukit Batu',
            'Rakumpit',
        ];

        foreach ($districts as $name) {
            District::create(['name' => $name]);
        }
    }
}