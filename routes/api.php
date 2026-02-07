<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Authenticate as AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\LocationsController;

// Login and Registration Routes
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Password Reset and Email Verification Routes
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::post('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');
Route::post('/email/resend', [AuthController::class, 'resendVerificationEmail'])->name('verification.resend'); //->middleware('auth:sanctum')

// Get Client and Location
Route::get('/clients', [ClientController::class, 'index']); //->middleware('auth:sanctum');
Route::get('/locations', [LocationsController::class, 'index']); //->middleware('auth:sanctum');

// Booking Routes
Route::apiResource('bookings', BookingController::class)->middleware('auth:sanctum');

// Dispatch Routes
Route::post('/dispatch/{consignment_no}', [BookingController::class, 'dispatchBooking']); //->middleware('auth:sanctum');

// Receive Routes
Route::post('/delivery/{consignment_no}', [BookingController::class, 'deliverBooking']);//->middleware('auth:sanctum');
