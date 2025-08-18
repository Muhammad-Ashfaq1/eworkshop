<?php

use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\VehiclePartController;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->name('admin.')->middleware('auth')->group(function(){
    Route::controller(LocationController::class)->prefix('location')
    ->name('location.')->group(function(){
        Route::get('/' ,  'index')->name('index');
        Route::post('store' ,  'store')->name('store');
        Route::get('edit/{id}','edit')->name('edit');
        Route::delete('destroy/{id}', 'destroy')->name('destroy');
    });
    
    // Vehicle Parts Routes
    Route::controller(VehiclePartController::class)->prefix('vehicle-parts')
    ->name('vehicle.part.')->group(function(){
        Route::get('/' ,  'index')->name('index');
        Route::post('store' ,  'store')->name('store');
        Route::delete('destroy/{id}', 'destroy')->name('destroy');


    });




});


?>
