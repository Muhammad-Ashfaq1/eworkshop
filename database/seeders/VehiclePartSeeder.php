<?php

namespace Database\Seeders;

use App\Models\VehiclePart;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class VehiclePartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('data/parts.csv');

        if (!File::exists($path)) {
            $this->command->error("CSV file not found at: {$path}");
            return;
        }

        $csvData = array_map('str_getcsv', file($path));
        $header = array_map('trim', array_shift($csvData)); // first row = headers

        foreach ($csvData as $row) {
            $row = array_combine($header, $row);

            VehiclePart::updateOrCreate(
                ['slug' => Str::slug($row['part'])],
                [
                    'name'      => $row['part'],
                    'slug'      => Str::slug($row['part']),
                    'is_active' => 1,
                    'e_id'      => isset($row['id']) ? (int)$row['id'] : null,
                ]
            );
        }

        $this->command->info("Vehicle parts seeded successfully!");
    }
}
