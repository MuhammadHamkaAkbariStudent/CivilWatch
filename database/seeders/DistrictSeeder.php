<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\District;

class DistrictSeeder extends Seeder
{
    public function run(): void
    {
        // Daftar 5 Kecamatan resmi di Kota Banjarmasin
        $districts = [
            'Banjarmasin Tengah',
            'Banjarmasin Utara',
            'Banjarmasin Selatan',
            'Banjarmasin Barat',
            'Banjarmasin Timur',
        ];

        foreach ($districts as $name) {
            District::create(['name' => $name]);
        }
    }
}