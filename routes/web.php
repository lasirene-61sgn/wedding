<?php

use App\Http\Controllers\Admin\AdminGuestListController;
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
use App\Http\Controllers\Admin\AdminVenueController;
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
use App\Http\Controllers\GuestRSVPController;
use App\Http\Controllers\Host\AccommodationController;
use App\Http\Controllers\Host\AutomationController;
use App\Http\Controllers\Host\BudgetController;
use App\Http\Controllers\Host\CallCenterController;
use App\Http\Controllers\Host\ChatController;
use App\Http\Controllers\Host\ChatWizardController;
use App\Http\Controllers\Host\ChecklistController;
use App\Http\Controllers\Host\ContactController;
use App\Http\Controllers\Host\ContractController;
use App\Http\Controllers\Host\DocumentController;
use App\Http\Controllers\Host\HelpingStaffController;
use App\Http\Controllers\Host\HostFamilyController;
use App\Http\Controllers\Host\LogisticsController;
use App\Http\Controllers\Host\MasterController;
use App\Http\Controllers\Host\MemberController;
use App\Http\Controllers\Host\MenuController;
use App\Http\Controllers\Host\MessagingController;
use App\Http\Controllers\Host\MoodBoardController;
use App\Http\Controllers\Host\NotificationController;
use App\Http\Controllers\Host\SetupController;
use App\Http\Controllers\Host\TaskController;
use App\Http\Controllers\Host\TimelineController;
use App\Http\Controllers\Host\VendorController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/test-whatsapp', function () {
    // Fetches the absolute latest guest added to your database, ignoring Auth rules
    $guest = \App\Models\GuestList::latest()->first();

    if (!$guest) {
        return "The guest_lists table is completely empty. Please insert at least one test guest row into your database first, then refresh this page.";
    }

    // Temporarily force a phone number and relation for this explicit test run
    // Replace with your actual phone number to see the message on your device
    $guest->guest_number = '9361590913';
    $guest->whatsapp_number = '9361590913';
    $guest->relation = 'groom'; // Options: bride, groom_parent, groom

    $service = new \App\Services\InvitationService();

    // Fire the public delivery engine
    $service->sendBulkInvitations($guest, ['whatsapp'], 'Haldi, Mehendi, Reception');

    return "API execution triggered using Guest ID [{$guest->id}]: {$guest->guest_name}. Check your storage/logs/laravel.log now!";
});
Route::get('/rsvp/{id}', [GuestRSVPController::class, 'showPortal'])->name('guest.rsvp.portal');
Route::post('/rsvp/{id}/update', [GuestRSVPController::class, 'updateStatus'])->name('guest.rsvp.update');
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
        Route::get('/backgrounds', [CeramonyController::class, 'manageBackgrounds'])->name('ceramony.backgrounds.index');
        Route::post('/backgrounds', [CeramonyController::class, 'storeBackgrounds'])->name('ceramony.backgrounds.store');
        Route::delete('/backgrounds/{id}', [CeramonyController::class, 'destroyBackground'])->name('ceramony.backgrounds.destroy');
        Route::resource('invitation', HostInvitationController::class);
        Route::get('/guestlist', [AdminGuestListController::class, 'index'])->name('guestlist.index');
        Route::get('/guestlist/{id}', [AdminGuestListController::class, 'show'])->name('guestlist.show');
        Route::get('/guestlist/{id}/edit', [AdminGuestListController::class, 'edit'])->name('guestlist.edit');
        Route::put('/guestlist/{id}/update', [AdminGuestListController::class, 'update'])->name('guestlist.update');
        Route::delete('/guestlist/{id}/delete', [AdminGuestListController::class, 'destroy'])->name('guestlist.destroy');
        Route::delete('/guestlist/{id}/force-delete', [AdminGuestListController::class, 'forceDelete'])->name('guestlist.forceDelete');
        Route::resource('venues', AdminVenueController::class);
        
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
    Route::get('/register/verify-otp', [HostLoginController::class, 'showVerifyForm'])->name('verify.form');
    Route::post('/register/verify-otp', [HostLoginController::class, 'verifyOtp'])->name('verify.submit');
    Route::get('/auth/google', [HostLoginController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('/auth/google/callback', [HostLoginController::class, 'handleGoogleCallback']);
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password/send-otp', [ForgotPasswordController::class, 'requestOtp'])->name('password.otp.send');
    Route::get('/verify-otp', [ForgotPasswordController::class, 'showVerifyForm'])->name('password.verify.view');
    Route::post('/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('password.verify.submit');
    Route::get('/forgot-password/reset', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('password.reset.view');
    Route::post('/forgot-password/reset', [ForgotPasswordController::class, 'updatePassword'])->name('password.reset.submit');


    // --- AUTHENTICATED HOSTS ONLY ZONE ---
    Route::middleware(['auth:host'])->group(function () {
        Route::get('/set-password', [HostLoginController::class, 'showSetPasswordForm'])->name('set-password.view');
        Route::post('/set-password', [HostLoginController::class, 'storeSetPassword'])->name('set-password.submit');
        Route::get('/select-package', [PackageController::class, 'index'])->name('packages.index');
        Route::post('/select-package', [PackageController::class, 'select'])->name('packages.select');
        Route::get('/dashboard', [HostLoginController::class, 'dashboard'])->name('dashboard');
        Route::get('/logout', [HostLoginController::class, 'logout'])->name('host.login');
        Route::post('/logout', [HostLoginController::class, 'logout'])->name('logout');

        // ==========================================
        // PLUGGED IN: CHAT SETUP FORM WIZARD ROUTES
        // ==========================================
        Route::prefix('wizard')->name('wizard.')->group(function () {
            Route::get('/', [ChatWizardController::class, 'index'])->name('index'); 
            Route::post('/store-venue', [ChatWizardController::class, 'storeVenue'])->name('storeVenue');
            Route::post('/store-invitation', [ChatWizardController::class, 'storeInvitation'])->name('storeInvitation');
            Route::post('/store-savedate', [ChatWizardController::class, 'storeSaveDate'])->name('storeSaveDate');
            Route::post('/store-ceremony', [ChatWizardController::class, 'storeCeremony'])->name('storeCeremony');
        });

        // Your existing Resource Routes
        Route::post('venue/update/{id}', [VenueController::class, 'update'])->name('venue.custom_update');
        Route::resource('venue', VenueController::class);
        Route::post('/host/venue/store', [VenueController::class, 'store'])->name('host.venue.store');
        Route::resource('ceramony', HostCeramonyController::class);

        // Gallery Resources
        Route::resource('picture', PictureController::class);
        Route::resource('album', AlbumController::class);
        Route::resource('video', VideoController::class);
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

        // Planning Tools & Logistics
        Route::resource('vendors', VendorController::class);
        Route::resource('timeline', TimelineController::class);
        Route::resource('budget', BudgetController::class);
        Route::resource('checklist', ChecklistController::class);
        Route::resource('moodboard', MoodBoardController::class);
        Route::resource('logistics', LogisticsController::class);
        Route::resource('accommodation', AccommodationController::class);
        Route::resource('menus', MenuController::class);
        Route::resource('members', MemberController::class);
        Route::resource('helpingstaff', HelpingStaffController::class);

        // Communication & Setup
        Route::resource('messaging', MessagingController::class);
        Route::resource('chat', ChatController::class);
        Route::resource('callcenter', CallCenterController::class);
        Route::resource('contacts', ContactController::class);
        Route::resource('notifications', NotificationController::class);
        Route::resource('documents', DocumentController::class);
        Route::resource('contracts', ContractController::class);
        Route::resource('automation', AutomationController::class);
        Route::resource('setup', SetupController::class);
        Route::resource('master', MasterController::class);
        Route::resource('tasks', TaskController::class);
        Route::resource('hfamily', HostFamilyController::class);
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
        Route::post('/wedding/{id}/ceremony-status', [GuestInvitationController::class, 'updateCeremonyStatus'])->name('update_ceremony_status');

        // Dashboard and Gallery routes (always accessible now)
        Route::get('/wedding/{id}/ceremonies', [GuestInvitationController::class, 'showCeremonies'])->name('wedding.details');
        Route::get('/wedding/{id}/gallery', [GuestInvitationController::class, 'showGallery'])->name('gallery');
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
