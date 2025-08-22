<?php

namespace App\Interfaces;

use Illuminate\Http\JsonResponse;

interface DefectReportRepositoryInterface
{
    /**
     * Get defect reports listing for datatable
     * @param array $data
     * @param object $user
     * @return JsonResponse
     */
    public function getDefectReportListing($data, $user): JsonResponse;

    /**
     * Get defect report by ID with relationships
     * @param int $id
     * @return mixed
     */
    public function getDefectReportById($id);

    /**
     * Create defect report
     * @param array $data
     * @return JsonResponse
     */
    public function createDefectReport($data): JsonResponse;

    /**
     * Update defect report
     * @param int $id
     * @param array $data
     * @return JsonResponse
     */
    public function updateDefectReport($id, $data): JsonResponse;

    /**
     * Delete defect report
     * @param int $id
     * @return JsonResponse
     */
    public function deleteDefectReport($id): JsonResponse;

    /**
     * Get defect reports for user with pagination
     * @param object $user
     * @param int $perPage
     * @return mixed
     */
    public function getDefectReportsForUser($user, $perPage = 15);

    /**
     * Check if user can view the report
     * @param object $user
     * @param object $defectReport
     * @return bool
     */
    public function canViewReport($user, $defectReport): bool;
}
