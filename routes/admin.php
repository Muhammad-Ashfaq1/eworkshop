<?php

use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VehiclePartController;
use App\Http\Controllers\Admin\VehicleController;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->name('admin.')->middleware('auth')->group(function(){
    // User Management Routes - Super Admin only
    Route::controller(UserController::class)->prefix('users')
    ->name('user.')->middleware(['role:super_admin'])->group(function(){
        Route::get('/' , 'index')->name('index');
        Route::post('store' , 'store')->name('store');
        Route::get('show/{user}', 'show')->name('show');
        Route::put('update/{user}', 'update')->name('update');
        Route::delete('destroy/{user}', 'destroy')->name('destroy');
        Route::post('reset-password/{user}', 'resetPassword')->name('reset.password');
        Route::post('toggle-status/{user}', 'toggleStatus')->name('toggle.status');
    });

    // Location Management Routes
    Route::controller(LocationController::class)->prefix('location')
    ->name('location.')->middleware(['role_or_permission:super_admin|admin|read_locations'])->group(function(){
        Route::get('/' , 'index')->name('index');
        Route::post('store' , 'store')->name('store')->middleware(['permission:create_locations']);
        Route::get('edit/{id}','edit')->name('edit')->middleware(['permission:update_locations']);
        Route::delete('destroy/{id}', 'destroy')->name('destroy')->middleware(['permission:delete_locations']);
    });

    // Vehicle Parts Routes
    Route::controller(VehiclePartController::class)->prefix('vehicle-parts')
    ->name('vehicle.part.')->group(function(){
        Route::get('/' ,  'index')->name('index');
        Route::post('store' ,  'store')->name('store');
        Route::delete('destroy/{id}', 'destroy')->name('destroy');
        Route::get('edit/{id}','edit')->name('edit');
     });


    // Vehicle Routes
    Route::controller(VehicleController::class)->prefix('vehicles')
    ->name('vehicle.')->group(function(){
        Route::get('/' ,  'index')->name('index');

    });

});


?>
