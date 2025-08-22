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
     * Get vehicles report with filters and pagination
     * @param array $filters
     * @return JsonResponse
     */
    public function getVehiclesReport(array $filters): JsonResponse;

    /**
     * Get vehicles report with DataTables pagination
     * @param array $data
     * @return JsonResponse
     */
    public function getVehiclesReportListing(array $data): JsonResponse;

    /**
     * Get defect reports with filters
     * @param array $filters
     * @return JsonResponse
     */
    public function getDefectReportsReport(array $filters): JsonResponse;

    /**
     * Get defect reports with DataTables pagination
     * @param array $data
     * @return JsonResponse
     */
    public function getDefectReportsReportListing(array $data): JsonResponse;

    /**
     * Get vehicle parts report with filters
     * @param array $filters
     * @return JsonResponse
     */
    public function getVehiclePartsReport(array $filters): JsonResponse;

    /**
     * Get vehicle parts report with DataTables pagination
     * @param array $data
     * @return JsonResponse
     */
    public function getVehiclePartsReportListing(array $data): JsonResponse;

    /**
     * Get locations report with filters
     * @param array $filters
     * @return JsonResponse
     */
    public function getLocationsReport(array $filters): JsonResponse;

    /**
     * Get locations report with DataTables pagination
     * @param array $data
     * @return JsonResponse
     */
    public function getLocationsReportListing(array $data): JsonResponse;

    /**
     * Export report data
     * @param array $filters
     * @return JsonResponse
     */
    public function exportReport(array $filters): JsonResponse;
}
