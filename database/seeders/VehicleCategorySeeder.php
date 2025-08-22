<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $options = [
            'Arm Roll',
            'Chain Arm Roll',
            'Compactor',
            'Dumper',
            'Gully Sucker',
            'Loader',
            'Mech Washer',
            'Mini Dumper',
            'Miscelleneous',
            'Pickup',
            'Rikshaw',
            'Sweeper',
            'Tracker not Installed',
            'Trailer',
            'Water Bowser',
            'Cater Pillar',
            'Bolan',
            'Bike',
            'HiLux Dala',
            'Cultus',
            'FAW',
            'JEEP',
            'Corolla XLI',
            'Tractor Trolley',
            'Tractor Loadery',
            'Toyota Bus',
            'Mini Van',
            'Jimmy',
            'Honda City',
            'Corolla Altis',
            'Toyota Vigo',
            'Toyota Yaris',
            'Toyota Fortuner',
            'Vacuum Sweeper',
            'Pole Vehicle',
            'Other',
        ];

        foreach ($options as $option) {
            DB::table('vehicle_categories')->insert([
                'name' => $option,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
