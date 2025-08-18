<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\ProfileController;
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


//location routes
require __DIR__ .  '/admin.php';
