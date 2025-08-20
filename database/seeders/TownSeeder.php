<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Location;
class TownSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $towns = [
            'Allama Iqbal Town',
            'Aziz Bhatti Town',
            'DGBT',
            'Gulberg Town',
            'Nishtar Town',
            'Ravi Town',
            'Ring Road',
            'Samanabad Town',
            'Shalimar Town',
            'Wahga Town',
            'Night Operations',
            'Compost Plant',
            'Lakhodair',
            'Raigarh Centre',
            'RWMC',
            'Communication',
            'MBS Multan',
            'TR-Saggian',
            'TR-Valencia',
            'Pool Vehicle',
        ];

        $workshops = [
            'Children Workshop',
            'Outfall Road Workshop South',
            'Outfall Road Workshop North',
            'Thokari Workshop',
            'Compost Plant ',
        ];

        foreach ($workshops as $workshop) {
            Location::updateOrCreate([
                'name' => $workshop,
            ], [
                'location_type' => Location::LOCATION_TYPE_WORKSHOP,
                'is_active' => Location::IS_ACTIVE,
            ]);
        }

        foreach ($towns as $town) {
            Location::updateOrCreate([
                'name' => $town,
            ], [
                'location_type' => Location::LOCATION_TYPE_TOWN,
                'is_active' => Location::IS_ACTIVE,
            ]);
        }
    }
}
