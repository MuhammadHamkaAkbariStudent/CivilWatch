<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1 Admin account
        User::create([
            'name'     => 'Admin CivilWatch',
            'email'    => 'admin@civilwatch.id',
            'password' => 'password',
            'role'     => 'admin',
        ]);

        // 5 Citizen accounts
        $citizens = [
            ['name' => 'Budi Santoso',  'email' => 'budi@gmail.com'],
            ['name' => 'Siti Rahayu',   'email' => 'siti@gmail.com'],
            ['name' => 'Agus Prasetyo', 'email' => 'agus@gmail.com'],
            ['name' => 'Dewi Lestari',  'email' => 'dewi@gmail.com'],
            ['name' => 'Eko Widodo',    'email' => 'eko@gmail.com'],
        ];

        foreach ($citizens as $citizen) {
            User::create([
                'name'     => $citizen['name'],
                'email'    => $citizen['email'],
                'password' => 'password',
                'role'     => 'citizen',
            ]);
        }
    }
}