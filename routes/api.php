<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;

Route::prefix('v1')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);              // /api/v1/auth/login
        Route::post('register', [AuthController::class, 'register']);        // /api/v1/auth/register
        Route::post('refresh', [AuthController::class, 'refresh'])->middleware('jwt.auth'); // /api/v1/auth/refresh
        Route::post('logout', [AuthController::class, 'logout'])->middleware('jwt.auth');   // /api/v1/auth/logout

        // OTP endpoints
        Route::post('send-otp', [AuthController::class, 'sendOtp']);  // directly when user registered no need here 
        Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
    });

    Route::post('forgot-password', [AuthController::class, 'forgotPasswordSendOtp']);   // /api/v1/forgot-password
    Route::post('verify-forgot-password-otp', [AuthController::class, 'verifyForgotPasswordOtp']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);     // /api/v1/reset-password

    Route::middleware(['jwt.auth'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index']);                 // /api/v1/dashboard
        Route::get('profile', [DashboardController::class, 'profile']);                 // /api/v1/dashboard

        Route::post('users/change-password', [AuthController::class, 'changePassword']); // /api/v1/change-password (you can alias)

        // Rekognition endpoints
        Route::post('rekognition/detect', [\App\Http\Controllers\RekognitionController::class, 'detectFaces']);
        Route::post('rekognition/upload-index', [\App\Http\Controllers\RekognitionController::class, 'uploadAndIndex']);
        Route::post('rekognition/identify', [\App\Http\Controllers\RekognitionController::class, 'identify']);


        // Admin routes
        Route::get('admin/users', [AdminController::class, 'usersList']);
        Route::post('admin/users/{user}/approve', [AdminController::class, 'approveUser']);
        Route::post('admin/users/{user}/reject', [AdminController::class, 'rejectUser']);

        // Owner routes
        Route::get('owner/profile', [OwnerController::class, 'profile']);
        Route::post('owner/refresh-qr', [OwnerController::class, 'refreshQrCode']);

        // Staff routes
        Route::post('staff/scan-qr', [StaffController::class, 'scanQr']);
        Route::get('staff/entries', [StaffController::class, 'entriesList']);
    });
});
