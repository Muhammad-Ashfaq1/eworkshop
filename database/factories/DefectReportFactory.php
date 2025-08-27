<?php

namespace Database\Factories;

use App\Models\DefectReport;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DefectReport>
 */
class DefectReportFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DefectReport::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'vehicle_id' => null,
            'location_id' => null,
            'driver_name' => fake()->name(),
            'fleet_manager_id' => null,
            'mvi_id' => null,
            'date' => fake()->date(),
            'type' => DefectReport::TYPE_DEFECT_REPORT,
            'created_by' => User::factory(),
        ];
    }
}
