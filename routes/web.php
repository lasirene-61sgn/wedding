<?php

use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\CategoryVenueController;
use App\Http\Controllers\Admin\CeramonyController;
use App\Http\Controllers\Admin\GuestLoginController;
use App\Http\Controllers\Admin\HostController;
use App\Http\Controllers\Admin\HostInvitationController;
use App\Http\Controllers\Admin\PackageSelectController;
use App\Http\Controllers\Admin\PlannerLoginController;
use App\Http\Controllers\Admin\VendorLoginController;
use App\Http\Controllers\Admin\VenueLoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Guest\GuestInvitationController;
use App\Http\Controllers\Host\AlbumController;
use App\Http\Controllers\Host\CeramonyController as HostCeramonyController;
use App\Http\Controllers\Host\GuestCategoryController;
use App\Http\Controllers\Host\GuestListController;
use App\Http\Controllers\Host\HostLoginController;
use App\Http\Controllers\Host\InvitationController;
use App\Http\Controllers\Host\PackageController;
use App\Http\Controllers\Host\PictureController;
use App\Http\Controllers\Host\ProfileController;
use App\Http\Controllers\Host\ReportController;
use App\Http\Controllers\Host\SaveDateController;
use App\Http\Controllers\Host\VenueController;
use App\Http\Controllers\Host\VideoController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('login.submit');

    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/dashboard', [AdminLoginController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
        Route::resource('host', HostController::class);
        Route::resource('package', PackageSelectController::class);
        Route::resource('categoryvenue', CategoryVenueController::class);
        Route::resource('ceramony', CeramonyController::class);
        Route::resource('invitation', HostInvitationController::class);
    });

    Route::middleware(['auth:host'])->group(function () {
        Route::get('/check-status', function () {
            return response()->json(['status' => Auth::guard('host')->user()->status]);
        })->name('check.status');
    });
});

Route::group(['prefix' => 'host', 'as' => 'host.'], function () {
    Route::get('/login', [HostLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [HostLoginController::class, 'login'])->name('login.submit');
    Route::get('/register', [HostLoginController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [HostLoginController::class, 'register'])->name('register.submit');
    Route::get('/auth/google', [HostLoginController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('/auth/google/callback', [HostLoginController::class, 'handleGoogleCallback']);
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('password.request');
    Route::post('/forgot-password/send-otp', [ForgotPasswordController::class, 'requestOtp'])
        ->name('password.otp.send');
    Route::get('/verify-otp', [ForgotPasswordController::class, 'showVerifyForm'])
        ->name('password.verify.view');

    // 2. This processes the submitted 6-digit code
    Route::post('/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])
        ->name('password.verify.submit');
    // 1. Show the form where they type their new password
    Route::get('/forgot-password/reset', [ForgotPasswordController::class, 'showResetPasswordForm'])
        ->name('password.reset.view');

    // 2. Process and save the new password to the host database table
    Route::post('/forgot-password/reset', [ForgotPasswordController::class, 'updatePassword'])
        ->name('password.reset.submit');


    Route::middleware(['auth:host'])->group(function () {
        Route::get('/set-password', [HostLoginController::class, 'showSetPasswordForm'])->name('set-password.view');
        Route::post('/set-password', [HostLoginController::class, 'storeSetPassword'])->name('set-password.submit');
        Route::get('/select-package', [PackageController::class, 'index'])->name('packages.index');
        Route::post('/select-package', [PackageController::class, 'select'])->name('packages.select');
        Route::get('/dashboard', [HostLoginController::class, 'dashboard'])->name('dashboard');
        Route::get('/logout', [HostLoginController::class, 'logout'])->name('host.login');
        Route::post('/logout', [HostLoginController::class, 'logout'])->name('logout');

        Route::post('venue/update/{id}', [VenueController::class, 'update'])->name('venue.custom_update');
        Route::resource('venue', VenueController::class);
        Route::post('/host/venue/store', [VenueController::class, 'store'])->name('host.venue.store');
        Route::resource('ceramony', HostCeramonyController::class);

        // Gallery Resources
        Route::resource('picture', PictureController::class);
        Route::resource('album', AlbumController::class);
        Route::resource('video', VideoController::class);

        // FIX: Removed 'host.' from the name because the Group already adds it
        Route::delete('album/{id}/delete-image', [AlbumController::class, 'deleteImage'])->name('album.delete-image');

        Route::resource('invitation', InvitationController::class);

        Route::resource('savedate', SaveDateController::class);
        Route::post('guestlist/import', [GuestListController::class, 'import'])->name('guestlist.import');


        Route::resource('guestlist', GuestListController::class);
        Route::post('guestlist/bulk-send', [GuestListController::class, 'bulkSend'])->name('guestlist.bulkSend');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::resource('categories', GuestCategoryController::class);
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    });
});

Route::prefix('guest')->name('guest.')->group(function () {
    // Login routes
    Route::get('/login', [GuestInvitationController::class, 'showLogin'])->name('login');
    Route::post('/login', [GuestInvitationController::class, 'login'])->name('login.post');

    // Routes requiring phone session
    Route::middleware(['guest.auth'])->group(function () {
        Route::get('/select-wedding', [GuestInvitationController::class, 'selectWedding'])->name('select');
        Route::get('/get-previous-data', [GuestInvitationController::class, 'getPreviousDetails'])
            ->name('profile.get_previous');
        Route::get('/guest/profile/{id}', [GuestInvitationController::class, 'editProfile'])->name('profile.edit');
        Route::put('/guest/profile/{id}', [GuestInvitationController::class, 'updateProfile'])->name('profile.update');
        // Save the Date Page (Shows Accept/Reject buttons)
        Route::get('/wedding/{id}/save-the-date', [GuestInvitationController::class, 'saveTheDate'])->name('save_the_date');
        Route::post('/wedding/{id}/status', [GuestInvitationController::class, 'updateStatus'])->name('update_status');

        // PROTECTED ROUTES: Only visible if status == 'accepted'
        Route::middleware(['guest.accepted'])->group(function () {
            Route::get('/wedding/{id}/ceremonies', [GuestInvitationController::class, 'showCeremonies'])->name('wedding.details');
            Route::get('/wedding/{id}/gallery', [GuestInvitationController::class, 'showGallery'])->name('gallery');
        });
    });
});

Route::group(['prefix' => 'planner', 'as' => 'planner.'], function () {
    Route::get('/login', [PlannerLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [PlannerLoginController::class, 'login'])->name('login.submit');

    Route::middleware(['auth:planner'])->group(function () {
        Route::get('/dashboard', [PlannerLoginController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [PlannerLoginController::class, 'logout'])->name('logout');
    });
});

Route::group(['prefix' => 'vendor', 'as' => 'vendor.'], function () {
    Route::get('/login', [VendorLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [VendorLoginController::class, 'login'])->name('login.submit');

    Route::middleware(['auth:vendor'])->group(function () {
        Route::get('/dashboard', [VendorLoginController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [VendorLoginController::class, 'logout'])->name('logout');
    });
});


Route::group(['prefix' => 'venue', 'as' => 'venue.'], function () {
    Route::get('/login', [VenueLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [VenueLoginController::class, 'login'])->name('login.submit');

    Route::middleware(['auth:venue'])->group(function () {
        Route::get('/dashboard', [VenueLoginController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [VenueLoginController::class, 'logout'])->name('logout');
    });
});
