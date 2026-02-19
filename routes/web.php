<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\BookingController;

// Guest routes for login and registration
Route::group(['middleware' => 'guest'], function () {
    // Home route redirects to login
    Route::get('/', fn() => redirect('/login'));

    // Login routes
    Route::get('/login', fn() => view('login'));
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    // Registration routes
    // Route::get('/register', fn() => view('register'));
    // Route::post('/register', [AuthController::class, 'register'])->name('register');

    // Forgot password
    Route::get('/forgot-password', fn() => view('forgot-password'))->name('forgot-password');
    Route::post('/forgot-password', [AuthController::class, 'forgot_password'])->name('forgot-password');

    // Reset password
    Route::get('/reset-password/{token}', [AuthController::class, 'showForm'])
        ->name('password.reset');

    Route::post('/reset-password', [AuthController::class, 'reset'])
        ->name('password.update');
});

// Authenticated routes
Route::group(['middleware' => 'auth'], function () {
    // Logout route
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard route
    Route::get('/dashboard', [BookingController::class, 'dashboard'])->name('dashboard');

    // User management routes
    Route::get('/dashboard/users', [UserController::class, 'index'])->name('dashboard.users');
    Route::get('/dashboard/users/new', [UserController::class, 'create'])->name('dashboard.users.new');
    Route::post('/dashboard/users/store', [UserController::class, 'store'])->name('dashboard.users.store');
    Route::get('/dashboard/users/edit/{id}', [UserController::class, 'edit'])->name('dashboard.users.edit');
    Route::patch('/dashboard/users/update/{id}', [UserController::class, 'update'])->name('dashboard.users.update');
    Route::delete('/dashboard/users/destroy/{id}', [UserController::class, 'destroy'])->name('dashboard.users.destroy');

    // Client management routes
    Route::resource('/dashboard/clients', ClientController::class);
    // Location management routes
    Route::resource('/dashboard/locations', LocationController::class);
    // Role management routes
    Route::resource('/dashboard/roles', RoleController::class);
    // Booking management routes
    Route::resource('/dashboard/bookings', BookingController::class);
    // Change password
    Route::view('/dashboard/change-password', 'dashboard.users.change-password')->name('dashboard.change-password');
    Route::post('/dashboard/change-password', [AuthController::class, 'change_password'])->name('dashboard.update-password');

    // Report generation
    Route::get('/dashboard/report/search', [BookingController::class, 'show_report_form'])->name('dashboard.show-report-form');
    Route::post('/dashboard/report/filter', [BookingController::class, 'filter_report_data'])->name('dashboard.filter-report-data');
    Route::post('/dashboard/report/download', [BookingController::class, 'generate_report'])->name('dashboard.report.download');
    Route::get('/download-image/{filename}', [BookingController::class, 'download'])->name('download.image');
});
