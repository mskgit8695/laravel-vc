<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Guest routes for login and registration
Route::group(['middleware' => 'guest'], function () {
    // Home route redirects to login
    Route::get('/', fn() => redirect('/login'));

    // Login routes
    Route::get('/login', fn() => view('login'));
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    // Registration routes
    Route::get('/register', fn() => view('register'));
    Route::post('/register', [AuthController::class, 'register'])->name('register');
});

// Authenticated routes
Route::group(['middleware' => 'auth'], function () {
    // Logout route
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard route
    Route::get('/dashboard', fn() => view('dashboard.dashboard'))->name('dashboard');
});
