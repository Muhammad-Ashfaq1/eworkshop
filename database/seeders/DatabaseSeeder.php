<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\VehicleCategorySeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
        VehicleCategorySeeder::class,
    ]);


        User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@eworkshop.com',
            'password' => 'password'
        ]);
        $admin = User::where('email' , 'admin@eworkshop.com')->first();
        if ($admin) {
            $admin->password = 'password';
            $admin->is_active = true;
            $admin->save();
        }
    }
}
