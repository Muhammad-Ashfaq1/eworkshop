<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\VehicleCategory;
use App\Http\Controllers\Controller;
class VehicleCategoriesController extends Controller
{
      public function index()
    {
        $vehicle_categories=VehicleCategory::latest()->get();
        return view('admin.vehicle-categories.index',compact('vehicle_categories'));
    }
    public function store(Request $request)
    {
            
             $vehicle_category_id=$request->vehicle_category_id ?? null;
            VehicleCategory::updateOrCreate(
            ['id' => $vehicle_category_id],
            [
                'name' => $request->vehicle_category_name,
                'is_active' => $request->is_active
             ]);

       return $this->getLatestRecords('Record Saved Successfully!');
    }


     public function edit($id)
    {
        $vehicle_category = VehicleCategory::findOrFail($id);
        if(!empty($vehicle_category)){
            return response()->json([
                'success' => true,
                'data' => $vehicle_category
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Vehicle Category not found'
        ]);
    }

    //delete

    public function destroy(String $id)
    {
        $vehicleCategory = VehicleCategory::findOrFail($id);
        if(!empty($vehicleCategory)){
            $vehicleCategory->delete();
            return $this->getLatestRecords('Vehicle Category deleted successfully.');
        }
        return back()->with(['success' => false]);
    }

     public function getLatestRecords($message= ''){
        $vehicle_categories = VehicleCategory::latest()->get();
        return response()->json([
            'success' => true,
            'message' => $message,
            'html'=>view('admin.vehicle-categories.data-table',compact('vehicle_categories'))->render(),
        ]) ;
    }

    public function archieved()
    {

        $archivedVehicleCategories = VehicleCategory::onlyTrashed()->get();
        return view('admin.vehicle-categories.archieved', compact('archivedVehicleCategories'));
    }

    //restore archived

    public function restoreArchived($id)
    {
         $vehicleCategory = VehicleCategory::withTrashed()->find($id);

        if (!$vehicleCategory) {
            return response()->json([
                'success' => false,
                'message' => ' Vehicle Category  not found'
            ], 404);
        }

         $vehicleCategory->restore();

        return response()->json([
            'success' => true,
            'message' => ' Vehicle Category restored successfully'
        ]);

    }
}
