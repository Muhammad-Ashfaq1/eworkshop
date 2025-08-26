<?php

namespace App\Http\Controllers\Admin;

use App\Models\FleetManager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\FleetMviStoreRequest;

class FleetManagerController extends Controller
{
    public function index()
    {
        $fleetManagers = FleetManager::latest()->get();
        return view('admin.fleet-manager.index',compact('fleetManagers'));
    }
    public function store(FleetMviStoreRequest $request)
    {
        $fleet_manager_id=$request->fleet_manager_id ?? null;
        FleetManager::updateOrCreate(
            ['id' => $fleet_manager_id],
            [
                'name' => $request->name,
                'type' => $request->type,
                'is_active' => $request->is_active
             ]);

       return $this->getLatestRecords('Record Save Successfully!');
    }
    public function destroy($id)
    {
        $fleetManager = FleetManager::findOrFail($id);
        if(!empty($fleetManager)){
            $fleetManager->delete();
            return $this->getLatestRecords('Fleet Manager/Mvi deleted successfully.');
        }
        return back()->with(['success' => false]);
    }


    public function getLatestRecords($message= ''){
        $fleetManagers = FleetManager::latest()->get();
        return response()->json([
            'success' => true,
            'message' => $message,
            'html'=>view('admin.fleet-manager.data-table',compact('fleetManagers'))->render(),
        ]) ;
    }
    public function edit($id)
    {
        $fleetManager = FleetManager::findOrFail($id);
        if(!empty($fleetManager)){
            return response()->json([
                'success' => true,
                'data' => $fleetManager
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Fleet Manager/Mvi not found'
        ]);
        return $this->getLatestRecords('Record Save Successfully!');

    }



}


