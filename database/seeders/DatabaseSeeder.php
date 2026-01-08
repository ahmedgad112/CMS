<?php

namespace Database\Seeders;

use App\Models\User;
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
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@clinic.com',
            'password' => 'password', // Laravel will hash it automatically due to 'hashed' cast
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create Doctor
        User::create([
            'name' => 'Dr. Ahmed',
            'email' => 'doctor@clinic.com',
            'password' => 'password',
            'role' => 'doctor',
            'is_active' => true,
        ]);

        // Create Receptionist
        User::create([
            'name' => 'Receptionist',
            'email' => 'receptionist@clinic.com',
            'password' => 'password',
            'role' => 'receptionist',
            'is_active' => true,
        ]);

        // Create Accountant
        User::create([
            'name' => 'Accountant',
            'email' => 'accountant@clinic.com',
            'password' => 'password',
            'role' => 'accountant',
            'is_active' => true,
        ]);

        // Create Storekeeper
        User::create([
            'name' => 'Storekeeper',
            'email' => 'storekeeper@clinic.com',
            'password' => 'password',
            'role' => 'storekeeper',
            'is_active' => true,
        ]);
    }
}
