<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@mail.com',
            'password' => Hash::make('password'),
        ]);
        $superAdmin->assignRole('Super Admin');

        // Manager
        $manager = User::create([
            'name' => 'Manager User',
            'email' => 'manager@mail.com',
            'password' => Hash::make('password'),
        ]);
        $manager->assignRole('Manager');

        // Staff
        $staff = User::create([
            'name' => 'Staff User',
            'email' => 'staff@mail.com',
            'password' => Hash::make('password'),
        ]);
        $staff->assignRole('Staff');

        // Finance
        $finance = User::create([
            'name' => 'Finance User',
            'email' => 'finance@mail.com',
            'password' => Hash::make('password'),
        ]);
        $finance->assignRole('Finance');
    }
}
