<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DefectReportController;
use App\Http\Controllers\DropdownController;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
})->name('home')->middleware('auth');



Route::get('login',[AuthController::class, 'login'])->name('login');
Route::post('login',[AuthController::class,'loginAction'])->name('login.action');

Route::get('register',[AuthController::class, 'register'])->name('register');
Route::post('register',[AuthController::class, 'registerUser'])->name('register.user');

Route::post('logout',[AuthController::class, 'logout'])->name('logout');

Route::get('profile', [ProfileController::class, 'profile'])->middleware('auth')->name('profile');

Route::post('logout',[AuthController::class, 'logout'])->name('logout');
Route::post('update/{id}',[ProfileController::class, 'update'])->middleware('auth')->name('update.user');
Route::post('updatePassword/{id}',[AuthController::class, 'updatePassword'])->middleware('auth')->name('update.password');


Route::get('/forgot-password', [PasswordController::class, 'forgotPassword'])->name('auth.forgot.password');
Route::post('/forgot-password-link', [PasswordController::class, 'forgotPasswordLink'])->name('auth.forgot.password.link');
Route::get('/forgot-password/{token}/{email}', [PasswordController::class, 'verifyEmail'])->name('auth.forgot.password.verify');
Route::post('/reset-password',[PasswordController::class, 'resetPassword'])->name('reset.password');
Route::get('/verify-user/{id}',[AuthController::class, 'verifyUser'])->name('verify.user');


Route::get('get-towns', [DropdownController::class, 'getTowns'])->name('dropdown.getTowns');
Route::get('get-vehicle-categories', [DropdownController::class, 'getVehicleCategories'])->name('dropdown.getVehicleCategories');
Route::get('get-vehicles', [DropdownController::class, 'getVehicles'])->name('dropdown.getVehicles');
Route::get('get-locations', [DropdownController::class, 'getLocations'])->name('dropdown.getLocations');
Route::get('get-fleet-managers', [DropdownController::class, 'getFleetManagers'])->name('dropdown.getFleetManagers');
Route::get('get-mvis', [DropdownController::class, 'getMvis'])->name('dropdown.getMvis');

// Defect Reports routes
Route::resource('defect-reports', DefectReportController::class)->except(['create', 'edit', 'show']);

// Role-specific dashboard routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/super-admin', [DashboardController::class, 'superAdmin'])->name('dashboard.super_admin');
    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
    Route::get('/dashboard/deo', [DashboardController::class, 'deo'])->name('dashboard.deo');
    Route::get('/dashboard/fleet-manager', [DashboardController::class, 'fleetManager'])->name('dashboard.fleet_manager');
    Route::get('/dashboard/mvi', [DashboardController::class, 'mvi'])->name('dashboard.mvi');
});

//location routes
require __DIR__ .  '/admin.php';
