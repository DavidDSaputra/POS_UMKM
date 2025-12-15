<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Owner account
        User::create([
            'name' => 'Owner Demo',
            'email' => 'owner@demo.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);

        // Kasir account
        User::create([
            'name' => 'Kasir Demo',
            'email' => 'kasir@demo.com',
            'password' => Hash::make('password'),
            'role' => 'kasir',
        ]);

        // Additional kasir
        User::create([
            'name' => 'Kasir 2',
            'email' => 'kasir2@demo.com',
            'password' => Hash::make('password'),
            'role' => 'kasir',
        ]);
    }
}
