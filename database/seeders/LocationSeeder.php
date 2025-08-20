<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LocationSeeder extends Seeder
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
                'slug' => Str::slug($workshop),
            ]);
        }
        foreach ($towns as $town) {
            Location::updateOrCreate([
                'name' => $town,
            ], [
                'location_type' => Location::LOCATION_TYPE_TOWN,
                'is_active' => Location::IS_ACTIVE,
                'slug' => Str::slug($town),
            ]);
        }
    }
}
