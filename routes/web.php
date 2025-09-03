<?php

use App\Exports\DefectReportExport;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DefectReportController;
use App\Http\Controllers\DropdownController;
use App\Http\Controllers\PurchaseOrderController;
use App\Models\DefectReport;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home')->middleware('auth');

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'loginAction'])->name('login.action');

// Route::get('register', [AuthController::class, 'register'])->name('register');
// Route::post('register', [AuthController::class, 'registerUser'])->name('register.user');

Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('profile', [ProfileController::class, 'profile'])->middleware('auth')->name('profile');

Route::post('logout', [AuthController::class, 'logout'])->name('logout');
Route::post('update/{id}', [ProfileController::class, 'update'])->middleware('auth')->name('update.user');
Route::post('updatePassword/{id}', [AuthController::class, 'updatePassword'])->middleware('auth')->name('update.password');

// Route::get('/forgot-password', [PasswordController::class, 'forgotPassword'])->name('auth.forgot.password');
// Route::post('/forgot-password-link', [PasswordController::class, 'forgotPasswordLink'])->name('auth.forgot.password.link');
// Route::get('/forgot-password/{token}/{email}', [PasswordController::class, 'verifyEmail'])->name('auth.forgot.password.verify');
// Route::post('/reset-password', [PasswordController::class, 'resetPassword'])->name('reset.password');
Route::get('/verify-user/{id}', [AuthController::class, 'verifyUser'])->name('verify.user');

Route::controller(DropdownController::class)->name('dropdown.')->group(function () {
    Route::get('get-towns', 'getTowns')->name('getTowns');
    Route::get('get-vehicle-categories', 'getVehicleCategories')->name('getVehicleCategories');
    Route::get('get-vehicles', 'getVehicles')->name('getVehicles');
    Route::get('get-locations', 'getLocations')->name('getLocations');
    Route::get('get-fleet-managers','getFleetManagers')->name('getFleetManagers');
    Route::get('get-mvis', 'getMvis')->name('getMvis');
    Route::get('get-defect-reports', 'getDefectReports')->name('getDefectReports');
    Route::get('get-vehicle-parts', 'getVehicleParts')->name('getVehicleParts');
});

// Defect Reports routes with permission middleware
Route::middleware(['auth'])->controller(DefectReportController::class)->prefix('defect-reports')->name('defect-reports.')->group(function () {
    Route::get('/',  'index')->name('index')->middleware('permission:read_defect_reports');
    Route::get('/create','create')->name('create')->middleware('permission:create_defect_reports');
    Route::get('/listing','getDefectReportListing')->name('listing')->middleware('permission:read_defect_reports');
    Route::get('/export','exportReports')->name('export')->middleware('permission:export_data');
    Route::post('/','store')->name('store')->middleware('permission:create_defect_reports');
    // Route::get('/{defectReport}','show')->name('show')->middleware('permission:read_defect_reports');
    Route::get('/{defectReport}/edit','edit')->name('edit')->middleware('permission:read_defect_reports');
    Route::put('/{defectReport}','update')->name('update')->middleware('permission:update_defect_reports');
    Route::delete('/{defectReport}','destroy')->name('destroy')->middleware('permission:delete_defect_reports');
    Route::get('/archieved','archieved')->name('archieved');
    Route::get('/restore-archieved/{id}','restoreArchieved')->name('restore.archieved');
});

// Purchase Orders routes with permission middleware
Route::middleware(['auth'])->controller(PurchaseOrderController::class)->prefix('purchase-orders')->name('purchase-orders.')->group(function () {
    Route::get('/', 'index')->name('index')->middleware('permission:read_purchase_orders');
    Route::get('/listing' ,'getPurchaseOrderListing')->name('listing')->middleware('permission:read_purchase_orders');
    Route::post('/', 'store')->name('store')->middleware('permission:create_purchase_orders');
    Route::get('/archived','archived')->name('archived')->middleware('permission:read_purchase_orders');
    Route::post('/restore-archived/{id}','restoreArchived')->name('restore.archived')->middleware('permission:restore_purchase_orders');
    Route::get('/{purchaseOrder}', 'show')->name('show')->middleware('permission:read_purchase_orders');
    Route::get('/{purchaseOrder}/edit', 'edit')->name('edit')->middleware('permission:read_purchase_orders');
    Route::put('/{purchaseOrder}','update')->name('update')->middleware('permission:update_purchase_orders');
    Route::delete('/{purchaseOrder}','destroy')->name('destroy')->middleware('permission:delete_purchase_orders');
});

// Role-specific dashboard routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/super-admin', [DashboardController::class, 'superAdmin'])->name('dashboard.super_admin');
    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
    Route::get('/dashboard/deo', [DashboardController::class, 'deo'])->name('dashboard.deo');
});

// location routes
require __DIR__.'/admin.php';
