<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use App\Models\Location;
use Illuminate\Support\Str;
use App\Models\VehicleCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
           $path = database_path('data/vehicles.csv');

        if (!File::exists($path)) {
            $this->command->error("CSV file not found at: {$path}");
            return;
        }

        $csvData = array_map('str_getcsv', file($path));
        $header = array_map('trim', array_shift($csvData));

        foreach ($csvData as $row) {
            $row = array_combine($header, $row);


            $category_id = VehicleCategory::whereRaw('LOWER(name) = ?', [strtolower($row['category'])])->value('id');

            if(empty($category_id) && $row['category'] == 'Tracker not Installe')
            {
                $category_id = VehicleCategory::whereRaw('LOWER(name) = ?', [strtolower('Tracker not Installed')])->value('id');
            }

            if (!$category_id) {
                $this->command->error("Category not found for vehicle: {$row['category']}");
                continue;
            }

            $location_id = Location::whereRaw('LOWER(name) = ?', [strtolower($row['vtown'])])->where('location_type', Location::LOCATION_TYPE_TOWN)->value('id');

            if (!$location_id) {
                $this->command->error("Location not found for vehicle: {$row['vtown']}");
                continue;
            }


            Vehicle::updateOrCreate(
                [
                    'vehicle_number' => $row['number'],
                ],
                [
                    'vehicle_number' => $row['number'],
                    'is_active' => $row['status'] === 'active' || $row['status'] === 'Active' ? 1 : 0,
                    'location_id' => $location_id,
                    'vehicle_category_id' => $category_id,
                    'condition' => $row['condition'] === 'Old' || $row['condition'] === 'old' ? Vehicle::CONDITION_OLD : Vehicle::CONDITION_NEW,
                    'e_id' => isset($row['id']) ? (int)$row['id'] : null,
                ]
            );
        }

        $this->command->info("Vehicle parts seeded successfully!");

    }
}
