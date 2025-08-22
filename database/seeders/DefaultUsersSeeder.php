<?php

namespace Database\Seeders;

use App\Constants\UserRoles;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin user
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'password' => Hash::make('password123'),
                'phone_number' => '+1234567890',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole(UserRoles::SUPER_ADMIN);

        // Create Admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'password' => Hash::make('password123'),
                'phone_number' => '+1234567891',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole(UserRoles::ADMIN);

        // Create DEO user
        $deo = User::firstOrCreate(
            ['email' => 'deo@example.com'],
            [
                'first_name' => 'Data Entry',
                'last_name' => 'Operator',
                'password' => Hash::make('password123'),
                'phone_number' => '+1234567892',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $deo->assignRole(UserRoles::DEO);

        // Create Fleet Manager user
        $fleetManager = User::firstOrCreate(
            ['email' => 'fleetmanager@example.com'],
            [
                'first_name' => 'Fleet',
                'last_name' => 'Manager',
                'password' => Hash::make('password123'),
                'phone_number' => '+1234567893',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $fleetManager->assignRole(UserRoles::FLEET_MANAGER);

        // Create MVI user
        $mvi = User::firstOrCreate(
            ['email' => 'mvi@example.com'],
            [
                'first_name' => 'Motor Vehicle',
                'last_name' => 'Inspector',
                'password' => Hash::make('password123'),
                'phone_number' => '+1234567894',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $mvi->assignRole(UserRoles::MVI);
    }
}
