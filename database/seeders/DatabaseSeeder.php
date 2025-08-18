<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::create([
        //     'first_name' => 'Admin',
        //     'last_name' => 'User',
        //     'email' => 'admin@auth.com',
        //     'password' => 'password'
        // ]);
        $admin = User::where('email' , 'admin@auth.com')->first();
        if ($admin) {
            $admin->password = 'password';
            $admin->is_active = true;
            $admin->save();
        }
    }
}
