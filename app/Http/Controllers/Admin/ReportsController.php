<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\ReportsRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    private $reportsRepository;

    public function __construct(ReportsRepositoryInterface $reportsRepository)
    {
        $this->reportsRepository = $reportsRepository;
    }

    public function index()
    {
        $this->authorize('access_admin_panel');
        
        // Get filter options for dropdowns
        $filterOptions = $this->reportsRepository->getFilterOptions();
        
        return view('admin.reports.index', compact('filterOptions'));
    }

    /**
     * Get vehicles report with filters
     */
    public function getVehiclesReport(Request $request): JsonResponse
    {
        $this->authorize('access_admin_panel');
        return $this->reportsRepository->getVehiclesReport($request->all());
    }

    /**
     * Get defect reports with filters
     */
    public function getDefectReportsReport(Request $request): JsonResponse
    {
        $this->authorize('access_admin_panel');
        return $this->reportsRepository->getDefectReportsReport($request->all());
    }

    /**
     * Get vehicle parts report with filters
     */
    public function getVehiclePartsReport(Request $request): JsonResponse
    {
        $this->authorize('access_admin_panel');
        return $this->reportsRepository->getVehiclePartsReport($request->all());
    }

    /**
     * Get locations report with filters
     */
    public function getLocationsReport(Request $request): JsonResponse
    {
        $this->authorize('access_admin_panel');
        return $this->reportsRepository->getLocationsReport($request->all());
    }

    /**
     * Get vehicles report with DataTables pagination
     */
    public function getVehiclesReportListing(Request $request): JsonResponse
    {
        $this->authorize('access_admin_panel');
        return $this->reportsRepository->getVehiclesReportListing($request->all());
    }

    /**
     * Get defect reports with DataTables pagination
     */
    public function getDefectReportsReportListing(Request $request): JsonResponse
    {
        $this->authorize('access_admin_panel');
        return $this->reportsRepository->getDefectReportsReportListing($request->all());
    }

    /**
     * Get vehicle parts report with DataTables pagination
     */
    public function getVehiclePartsReportListing(Request $request): JsonResponse
    {
        $this->authorize('access_admin_panel');
        return $this->reportsRepository->getVehiclePartsReportListing($request->all());
    }

    /**
     * Get locations report with DataTables pagination
     */
    public function getLocationsReportListing(Request $request): JsonResponse
    {
        $this->authorize('access_admin_panel');
        return $this->reportsRepository->getLocationsReportListing($request->all());
    }

    /**
     * Export report data
     */
    public function exportReport(Request $request): JsonResponse
    {
        $this->authorize('export_data');
        return $this->reportsRepository->exportReport($request->all());
    }
}
