<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FleetManager;
use Illuminate\Http\Request;

class FleetManagerController extends Controller
{
    public function index()
    {
        $fleetManagers = FleetManager::latest()->get();
        return view('admin.fleet-manager.index',compact('fleetManagers'));
    }
    public function addfleetmanager(Request $request)
    {
        $validated = $request->validate([
            'fleetManager' => 'required|string|max:255',
            'type' => 'required|in:fleet_manager,mvi',
            'is_active' => 'sometimes|boolean',
        ] );
        FleetManager::create([
            'name' => $request->fleetManager,
            'type' => $request->type,
            'is_active' => $request->is_active
        ]);
        //return redirect()->route('admin.fleet-manager.index')->with('success', 'Fleet Manager/Mvi added successfully.');
        $fleetManagers=FleetManager::latest()->get();
        return response()->json(['success' => true,
         'message' => 'Fleet Manager/Mvi added successfully.',
        'html'=>view('admin.fleet-manager.data-table',compact('fleetManagers'))->render()

        ]);
    }

    }


