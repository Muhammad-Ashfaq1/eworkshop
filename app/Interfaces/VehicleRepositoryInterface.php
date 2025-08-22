<?php

namespace App\Interfaces;

use Illuminate\Http\JsonResponse;

interface VehicleRepositoryInterface
{
    /**
     * Get vehicles listing for datatable
     * @param array $data
     * @return JsonResponse
     */
    public function getVehicleListing($data): JsonResponse;

    /**
     * Get vehicle by ID with relationships
     * @param int $id
     * @return mixed
     */
    public function getVehicleById($id);

    /**
     * Create or update vehicle
     * @param array $data
     * @return JsonResponse
     */
    public function createOrUpdateVehicle($data): JsonResponse;

    /**
     * Delete vehicle
     * @param int $id
     * @return JsonResponse
     */
    public function deleteVehicle($id): JsonResponse;

    /**
     * Get all vehicles with relationships for initial load
     * @return mixed
     */
    public function getAllVehiclesWithRelations();
}
