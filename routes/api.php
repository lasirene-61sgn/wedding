<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiGuestInvitationController;

// Group for all guest API routes
Route::prefix('guest')->name('api.guest.')->group(function () {
    // Login
    Route::post('/login', [ApiGuestInvitationController::class, 'login'])->name('login');

    // Logout
    Route::post('/logout', [ApiGuestInvitationController::class, 'logout'])->name('logout');

    // All routes that require the phone number in headers/token
    Route::middleware([\App\Http\Middleware\ApiGuestAuth::class])->group(function () {
        Route::get('/select-wedding', [ApiGuestInvitationController::class, 'selectWedding'])->name('select');
        Route::get('/get-previous-data', [ApiGuestInvitationController::class, 'getPreviousDetails']);
        
        Route::get('/profile', [ApiGuestInvitationController::class, 'getProfile'])->name('profile.get');
        Route::put('/profile', [ApiGuestInvitationController::class, 'updateProfile'])->name('profile.update');

        Route::get('/wedding/{id}/save-the-date', [ApiGuestInvitationController::class, 'saveTheDate'])->name('save_the_date');
        Route::post('/wedding/{id}/status', [ApiGuestInvitationController::class, 'updateStatus'])->name('update_status');
        Route::post('/wedding/{id}/ceremony-status', [ApiGuestInvitationController::class, 'updateCeremonyStatus'])->name('update_ceremony_status');

        Route::get('/wedding/{id}/ceremonies', [ApiGuestInvitationController::class, 'showCeremonies'])->name('wedding.details');
        Route::get('/wedding/{id}/gallery', [ApiGuestInvitationController::class, 'showGallery'])->name('gallery');
        Route::get('/wedding/{id}/hfamily', [ApiGuestInvitationController::class, 'showHostFamilyDetails'])->name('hfamily');
    });
});
