<?php

namespace App\Interfaces;

use Illuminate\Http\JsonResponse;

interface VehiclePartRepositoryInterface
{
    /**
     * Get vehicle parts listing for datatable
     * @param array $data
     * @return JsonResponse
     */
    public function getVehiclePartListing($data): JsonResponse;

    /**
     * Get vehicle part by ID
     * @param int $id
     * @return mixed
     */
    public function getVehiclePartById($id);

    /**
     * Create or update vehicle part
     * @param array $data
     * @return JsonResponse
     */
    public function createOrUpdateVehiclePart($data): JsonResponse;

    /**
     * Delete vehicle part
     * @param int $id
     * @return JsonResponse
     */
    public function deleteVehiclePart($id): JsonResponse;
}
