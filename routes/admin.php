<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\VehiclePartController;
use App\Http\Controllers\Admin\FleetManagerController;
use App\Http\Controllers\Admin\VehicleCategoryController;

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    // User Management Routes - Super Admin only
    Route::controller(UserController::class)->prefix('users')
        ->name('user.')->middleware(['permission:create_users'])->group(function () {
            Route::get('/', 'index')->name('index')->middleware('permission:read_users');
            Route::post('store', 'store')->name('store');
            Route::get('show/{user}', 'show')->name('show')->middleware('permission:read_users');
            Route::put('update/{user}', 'update')->name('update')->middleware('permission:update_users');
            Route::delete('destroy/{user}', 'destroy')->name('destroy')->middleware('permission:delete_users');
            Route::post('reset-password/{user}', 'resetPassword')->name('reset.password')->middleware('permission:update_users');
            Route::post('toggle-status/{user}', 'toggleStatus')->name('toggle.status')->middleware('permission:update_users');
        });

    // Location Management Routes
    Route::controller(LocationController::class)->prefix('location')
        ->name('location.')->middleware(['permission:read_locations'])->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/listing', 'getLocationListing')->name('listing');
            Route::post('store', 'store')->name('store')->middleware(['permission:create_locations']);
            Route::get('edit/{id}', 'edit')->name('edit')->middleware(['permission:update_locations']);
            Route::delete('destroy/{id}', 'destroy')->name('destroy')->middleware(['permission:delete_locations']);
            Route::get('archieved', 'archieved')->name('archieved')->middleware(['permission:read_locations']);
            Route::get('restore/{id}', 'restore')->name('restore')->middleware(['permission:restore_locations']);
        });

    // Vehicle Parts Routes
    Route::controller(VehiclePartController::class)->prefix('vehicle-parts')
        ->name('vehicle.part.')->middleware(['permission:read_vehicle_parts'])->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/listing', 'getVehiclePartsListing')->name('listing');
            Route::post('store', 'store')->name('store')->middleware(['permission:create_vehicle_parts']);
            Route::delete('destroy/{id}', 'destroy')->name('destroy')->middleware(['permission:delete_vehicle_parts']);
            Route::get('edit/{id}', 'edit')->name('edit')->middleware(['permission:update_vehicle_parts']);
            Route::get('archived', 'archived')->name('archived')->middleware(['permission:read_vehicle_parts']);
            Route::post('restore-archived/{id}', 'restoreArchived')->name('restore.archived')->middleware(['permission:restore_vehicle_parts']);
        });

    // Vehicle Routes
    Route::controller(VehicleController::class)->prefix('vehicles')
        ->name('vehicle.')->middleware(['permission:read_vehicles'])->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/listing', 'getVehicleListing')->name('listing');
            Route::post('store', 'store')->name('store')->middleware(['permission:create_vehicles']);
            Route::get('edit/{id}', 'edit')->name('edit')->middleware(['permission:update_vehicles']);
            Route::delete('destroy/{id}', 'destroy')->name('destroy')->middleware(['permission:delete_vehicles']);
            Route::get('archived', 'archived')->name('archived')->middleware(['permission:read_vehicles']);
            Route::post('restore-archived/{id}', 'restoreArchived')->name('restore.archived')->middleware(['permission:restore_vehicles']);
        });

    //vehicle Categories
       Route::controller(VehicleCategoryController::class)->prefix('vehicle-categories')
        ->name('vehicle-categories.')->group(function () {
        Route::get('/','index')->name('index');
        Route::post('/store','store')->name('store');
        Route::get('/edit/{id}','edit')->name('edit');
        Route::delete('/destroy/{id}','destroy')->name('destroy');
        Route::get('archieved','archieved')->name('archieved');
        Route::post('restore-archived/{id}','restoreArchived')->name('restore.archived');

        });

    // Fleet Manager Routes
    Route::controller(FleetManagerController::class)->prefix('fleet-manager')->name('fleet-manager.')->middleware(['permission:read_fleet_manager'])->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store')->middleware(['permission:create_fleet_manager']);
        Route::delete('destroy/{id}', 'destroy')->name('destroy')->middleware(['permission:delete_fleet_manager']);
        Route::get('edit/{id}', 'edit')->name('edit')->middleware(['permission:update_fleet_manager']);
        Route::get('archived', 'archived')->name('archived')->middleware(['permission:read_fleet_manager']);
        Route::post('restore-archived/{id}', 'restoreArchived')->name('restore.archived')->middleware(['permission:restore_fleet_manager']);
    });

    // Reports Routes - Admin and Super Admin only
    Route::controller(ReportsController::class)->prefix('reports')
        ->name('reports.')->middleware(['permission:view_reports'])->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/vehicles', 'getVehiclesReport')->name('vehicles');
            Route::get('/defect-reports', 'getDefectReportsReport')->name('defect-reports');
            Route::get('/vehicle-parts', 'getVehiclePartsReport')->name('vehicle-parts');
            Route::get('/locations', 'getLocationsReport')->name('locations');
            Route::get('/vehicles/listing', 'getVehiclesReportListing')->name('vehicles.listing');
            Route::get('/defect-reports/listing', 'getDefectReportsReportListing')->name('defect-reports.listing');
            Route::get('/vehicle-parts/listing', 'getVehiclePartsReportListing')->name('vehicle-parts.listing');
            Route::get('/locations/listing', 'getLocationsReportListing')->name('locations.listing');
            Route::post('/export', 'exportReport')->name('export')->middleware(['permission:export_data']);
        });

        // Activity Logs - Super Admin only
        Route::controller(LogsController::class)->prefix('logs')->name('logs.')->middleware(['permission:view_report_logs'])->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}', 'getLogDetails')->name('details');
            Route::delete('/{id}', 'destroy')->name('destroy')->middleware('permission:delete_report_logs');
        });

});
