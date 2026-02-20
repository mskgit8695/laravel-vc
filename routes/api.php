<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Authenticate as AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\LocationsController;
use App\Http\Controllers\AuthController as PasswordController;

// Login and Registration Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Password Reset and Email Verification Routes
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// Verification
Route::post('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');

Route::middleware('auth:sanctum')->group(function () {
    // Fetch a user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Change password
    Route::post('/change-password', [PasswordController::class, 'change_password']);

    // Resend verification link
    Route::post('/email/resend', [AuthController::class, 'resendVerificationEmail'])->name('verification.resend');

    // Get Client and Location
    Route::get('/clients', [ClientController::class, 'index']);
    Route::get('/locations', [LocationsController::class, 'index']);

    // Booking Routes
    Route::apiResource('bookings', BookingController::class);
    // Final Booking
    Route::post('/finalbooking', [BookingController::class, 'finalBooking']);
    // Get final Booking Routes
    Route::get('/getfinalbooking', [BookingController::class, 'getFinalBooking']);

    // Dispatch Routes
    Route::post('/dispatch', [BookingController::class, 'dispatchDraftBooking']);
    Route::get('/getdraftdispatch', [BookingController::class, 'getDraftDispatch']);
    Route::post('/dispatchfinal', [BookingController::class, 'dispatchFinalBooking']);
    Route::get('/getfinaldispatch', [BookingController::class, 'getFinalDispatch']);

    // Receive Routes
    Route::post('/delivery/{consignment_no}', [BookingController::class, 'deliverBooking']);
    Route::get('/getdelivery/{consignment_no}', [BookingController::class, 'deliverBookingDetails']);
    Route::get('/getphoto/{photo_no}', [BookingController::class, 'getPhoto']);

    // Draft booking, dispatch by client id
    Route::get('/getdraftbookingbyclient/{clientId}', [BookingController::class, 'getDraftBookingByClientId']);
    Route::get('/getdraftdispatchbyclient/{clientId}', [BookingController::class, 'getDraftDispatchByClientId']);
    Route::get('/getfinalbookingbyclient/{clientId}', [BookingController::class, 'getFinalBookingByClientId']);

    // Pending bookings to dispatch
    Route::get('/getpendingdispatch', [BookingController::class, 'getPendingDispatch']);
});
