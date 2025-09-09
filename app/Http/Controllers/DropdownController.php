<?php

namespace App\Http\Controllers;

use App\Models\DefectReport;
use App\Models\FleetManager;
use App\Models\Location;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use App\Models\VehiclePart;
use Illuminate\Http\Request;

class DropdownController extends Controller
{
    public function getTowns(Request $request)
    {
        $towns = Location::where('location_type', Location::LOCATION_TYPE_TOWN)->orderBy('name', 'asc')->where('is_active', Location::IS_ACTIVE)->get(
            ['id', 'name']
        );

        return response()->json([
            'success' => true,
            'data' => $towns,
        ]);
    }

    public function getVehicleCategories(Request $request)
    {
        $categories = VehicleCategory::where('is_active', 1)->get(['id', 'name']);

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    public function getVehicles(Request $request)
    {
        $vehicles = Vehicle::with('category')
            ->where('is_active', 1)->orderBy('vehicle_number','asc')
            ->get(['id', 'vehicle_number', 'vehicle_category_id']);

        $formattedVehicles = $vehicles->map(function ($vehicle) {
            return [
                'id' => $vehicle->id,
                'text' => $vehicle->vehicle_number.' - '.($vehicle->category->name ?? 'N/A'),
                'name' => $vehicle->vehicle_number.' - '.($vehicle->category->name ?? 'N/A'),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formattedVehicles,
        ]);
    }

    public function getLocations(Request $request)
    {
        $locations = Location::where('is_active', 1)->orderBy('name', 'asc')->get(['id', 'name']);

        return response()->json([
            'success' => true,
            'data' => $locations,
        ]);
    }

    public function getFleetManagers(Request $request)
    {
        $managers = FleetManager::where('type', FleetManager::TYPE_FLEET_MANAGER)->where('is_active', FleetManager::ACTIVE_STATUS)->orderBy('name','asc')->get();

        $formattedManagers = $managers->map(function ($manager) {
            return [
                'id' => $manager->id,
                'text' => $manager->name,
                'name' => $manager->name,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formattedManagers,
        ]);
    }

    public function getMvis(Request $request)
    {
        $mvis = FleetManager::where('is_active', FleetManager::ACTIVE_STATUS)->where('type', FleetManager::TYPE_MVI)->orderBy('name','asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $mvis,
        ]);
    }

    public function getDefectReports(Request $request)
    {
        $excludePurchaseOrderId = $request->get('exclude_po_id');
        $includePurchaseOrderId = $request->get('include_po_id');

        $query = DefectReport::where('type', DefectReport::TYPE_DEFECT_REPORT)
            ->whereNotNull('reference_number');

        if ($excludePurchaseOrderId) {
            // For create mode: exclude defect reports that have purchase orders
            $query->whereDoesntHave('purchaseOrders');
        } elseif ($includePurchaseOrderId && is_numeric($includePurchaseOrderId)) {
            // For edit mode: include the current defect report + defect reports without POs
            $query->where(function($q) use ($includePurchaseOrderId) {
                $q->whereDoesntHave('purchaseOrders')
                  ->orWhereHas('purchaseOrders', function($poQuery) use ($includePurchaseOrderId) {
                      $poQuery->where('id', $includePurchaseOrderId);
                  });
            });
        } else {
            // Default: exclude defect reports that have purchase orders
            $query->whereDoesntHave('purchaseOrders');
        }

        $defectReports = $query->orderBy('created_at', 'desc')
            ->get(['id', 'reference_number']);

        $formattedDefectReports = $defectReports->map(function ($defectReport) {
            return [
                'id' => $defectReport->id,
                'text' => $defectReport->reference_number ?: 'N/A',
                'name' => $defectReport->reference_number ?: 'N/A',
                'reference_number' => $defectReport->reference_number ?: 'N/A',
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formattedDefectReports,
        ]);
    }

    public function getVehicleParts(Request $request)
    {
        $vehicleParts = VehiclePart::where('is_active', 1)
            ->orderBy('name', 'asc')
            ->get(['id', 'name']);

        $formattedVehicleParts = $vehicleParts->map(function ($part) {
            return [
                'id' => $part->id,
                'text' => $part->name,
                'name' => $part->name,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formattedVehicleParts,
        ]);
    }
}
