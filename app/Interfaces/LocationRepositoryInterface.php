<?php

namespace App\Interfaces;

use Illuminate\Http\JsonResponse;

interface LocationRepositoryInterface
{
    /**
     * Get locations listing for datatable
     * @param array $data
     * @return JsonResponse
     */
    public function getLocationListing($data): JsonResponse;

    /**
     * Get location by ID
     * @param int $id
     * @return mixed
     */
    public function getLocationById($id);

    /**
     * Create or update location
     * @param array $data
     * @return JsonResponse
     */
    public function createOrUpdateLocation($data): JsonResponse;

    /**
     * Delete location
     * @param int $id
     * @return JsonResponse
     */
    public function deleteLocation($id): JsonResponse;
}
