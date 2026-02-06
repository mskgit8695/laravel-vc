<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;

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

    // User management routes
    Route::get('/dashboard/users', [UserController::class, 'index'])->name('dashboard.users');
    Route::get('/dashboard/users/new', [UserController::class, 'create'])->name('dashboard.users.new');
    Route::post('/dashboard/users/store', [UserController::class, 'store'])->name('dashboard.users.store');
    Route::get('/dashboard/users/edit/{id}', [UserController::class, 'edit'])->name('dashboard.users.edit');
    Route::patch('/dashboard/users/update/{id}', [UserController::class, 'update'])->name('dashboard.users.update');
    Route::delete('/dashboard/users/destroy/{id}', [UserController::class, 'destroy'])->name('dashboard.users.destroy');
});
