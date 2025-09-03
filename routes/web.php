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

Route::get('get-towns', [DropdownController::class, 'getTowns'])->name('dropdown.getTowns');
Route::get('get-vehicle-categories', [DropdownController::class, 'getVehicleCategories'])->name('dropdown.getVehicleCategories');
Route::get('get-vehicles', [DropdownController::class, 'getVehicles'])->name('dropdown.getVehicles');
Route::get('get-locations', [DropdownController::class, 'getLocations'])->name('dropdown.getLocations');
Route::get('get-fleet-managers', [DropdownController::class, 'getFleetManagers'])->name('dropdown.getFleetManagers');
Route::get('get-mvis', [DropdownController::class, 'getMvis'])->name('dropdown.getMvis');
Route::get('get-defect-reports', [DropdownController::class, 'getDefectReports'])->name('dropdown.getDefectReports');
Route::get('get-vehicle-parts', [DropdownController::class, 'getVehicleParts'])->name('dropdown.getVehicleParts');

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
Route::middleware(['auth'])->group(function () {
    Route::get('purchase-orders', [PurchaseOrderController::class, 'index'])->name('purchase-orders.index')->middleware('permission:read_purchase_orders');
    Route::get('purchase-orders/listing', [PurchaseOrderController::class, 'getPurchaseOrderListing'])->name('purchase-orders.listing')->middleware('permission:read_purchase_orders');
    Route::post('purchase-orders', [PurchaseOrderController::class, 'store'])->name('purchase-orders.store')->middleware('permission:create_purchase_orders');
    Route::get('purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'show'])->name('purchase-orders.show')->middleware('permission:read_purchase_orders');
    Route::get('purchase-orders/{purchaseOrder}/edit', [PurchaseOrderController::class, 'edit'])->name('purchase-orders.edit')->middleware('permission:read_purchase_orders');
    Route::put('purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'update'])->name('purchase-orders.update')->middleware('permission:update_purchase_orders');
    Route::delete('purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'destroy'])->name('purchase-orders.destroy')->middleware('permission:delete_purchase_orders');
    Route::get('purchase-orders-archived', [PurchaseOrderController::class, 'archived'])->name('purchase-orders.archived')->middleware('permission:read_purchase_orders');
    Route::post('purchase-orders/restore-archived/{id}', [PurchaseOrderController::class, 'restoreArchived'])->name('purchase-orders.restore.archived')->middleware('permission:restore_purchase_orders');
});

// Role-specific dashboard routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/super-admin', [DashboardController::class, 'superAdmin'])->name('dashboard.super_admin');
    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
    Route::get('/dashboard/deo', [DashboardController::class, 'deo'])->name('dashboard.deo');
});

// location routes
require __DIR__.'/admin.php';
