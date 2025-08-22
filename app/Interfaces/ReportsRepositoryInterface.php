<?php

namespace App\Interfaces;

use Illuminate\Http\JsonResponse;

interface ReportsRepositoryInterface
{
    /**
     * Get filter options for all report types
     * @return array
     */
    public function getFilterOptions(): array;

    /**
     * Get vehicles report with filters
     * @param array $filters
     * @return JsonResponse
     */
    public function getVehiclesReport(array $filters): JsonResponse;

    /**
     * Get defect reports with filters
     * @param array $filters
     * @return JsonResponse
     */
    public function getDefectReportsReport(array $filters): JsonResponse;

    /**
     * Get vehicle parts report with filters
     * @param array $filters
     * @return JsonResponse
     */
    public function getVehiclePartsReport(array $filters): JsonResponse;

    /**
     * Get locations report with filters
     * @param array $filters
     * @return JsonResponse
     */
    public function getLocationsReport(array $filters): JsonResponse;

    /**
     * Export report data
     * @param array $filters
     * @return JsonResponse
     */
    public function exportReport(array $filters): JsonResponse;
}
