<?php

use App\Http\Controllers\Guest\GuestController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\MapController;
use App\Http\Controllers\User\PhotographersController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\SettingsController;
use App\Http\Controllers\User\SpotController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SpatialDataController;

use App\Http\Controllers\Admin\DirectoryController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\InboxController;
use Illuminate\Support\Facades\Route;

// ═══════════════════════════════════════════════════════════════════════
// ROOT — redirect based on auth state & role
// ═══════════════════════════════════════════════════════════════════════
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route(auth()->user()->dashboardRoute());
    }
    return redirect()->route('login');
});

// ═══════════════════════════════════════════════════════════════════════
// GUEST ROUTES — Public, no authentication required
// ═══════════════════════════════════════════════════════════════════════
Route::get('/privacy-policy',   [GuestController::class, 'privacy'])->name('guest.privacy');
Route::get('/terms-of-service', [GuestController::class, 'terms'])->name('guest.terms');
Route::get('/contact-support',  [GuestController::class, 'contact'])->name('guest.contact');
Route::post('/contact-support', [GuestController::class, 'storeContact'])->name('guest.contact.store');

// ═══════════════════════════════════════════════════════════════════════
// USER ROUTES — auth + role:user
// ═══════════════════════════════════════════════════════════════════════
Route::middleware(['auth', 'role:user'])->group(function () {

    Route::get('/dashboard',      [DashboardController::class,   'index'])->name('user.dashboard');
    Route::get('/map',            [MapController::class,          'index'])->name('user.map');
    Route::get('/photographers',  [PhotographersController::class,'index'])->name('user.photographers');
    Route::get('/photographers/{id}', [PhotographersController::class,'show'])->name('user.photographers.show');
    Route::get('/spots/{id}',     [SpotController::class,         'show'])->name('user.spots.show');
    Route::get('/profile',        [ProfileController::class,      'index'])->name('user.profile');
    Route::post('/profile/avatar',[ProfileController::class,      'updateAvatar'])->name('user.profile.avatar');
    Route::get('/settings',       [SettingsController::class,     'index'])->name('user.settings');
    Route::patch('/settings',     [SettingsController::class,     'update'])->name('user.settings.update');
    Route::delete('/settings/account', [SettingsController::class, 'destroy'])->name('user.account.destroy');
});

// ═══════════════════════════════════════════════════════════════════════
// ADMIN ROUTES — auth + role:admin
// ═══════════════════════════════════════════════════════════════════════
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {

    Route::get('/dashboard',      [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/users',          [UsersController::class,          'index'])->name('admin.users');

    // ── Admin Inbox (Contact Messages) ──────────────────────────────────
    Route::get('/inbox',          [InboxController::class, 'index'])->name('admin.inbox');
    Route::delete('/inbox/{id}',  [InboxController::class, 'destroy'])->name('admin.inbox.destroy');

    // ── Spatial Data (Spots + Routes/Zones) ─────────────────────────────
    Route::get('/spatial-data',                    [SpatialDataController::class, 'index'])->name('admin.spatial-data');
    Route::get('/spatial-data/spot/create',        [SpatialDataController::class, 'createSpot'])->name('admin.spots.create');
    Route::post('/spatial-data/spot',              [SpatialDataController::class, 'storeSpot'])->name('admin.spots.store');
    Route::get('/spatial-data/spot/{id}',          [SpatialDataController::class, 'show'])->name('admin.spots.show');
    Route::get('/spatial-data/spot/{id}/edit',     [SpatialDataController::class, 'editSpot'])->name('admin.spots.edit');
    Route::put('/spatial-data/spot/{id}',          [SpatialDataController::class, 'updateSpot'])->name('admin.spots.update');
    Route::delete('/spatial-data/spot/{id}',       [SpatialDataController::class, 'destroySpot'])->name('admin.spots.destroy');



    // ── Photographer Directory ───────────────────────────────────────────
    Route::get('/directory',            [DirectoryController::class, 'index'])->name('admin.directory');
    Route::get('/directory/create',     [DirectoryController::class, 'create'])->name('admin.directory.create');
    Route::post('/directory',           [DirectoryController::class, 'store'])->name('admin.directory.store');
    Route::get('/directory/{id}',       [DirectoryController::class, 'show'])->name('admin.directory.show');
    Route::put('/directory/{id}',       [DirectoryController::class, 'update'])->name('admin.directory.update');
    Route::delete('/directory/{id}',    [DirectoryController::class, 'destroy'])->name('admin.directory.destroy');

});

require __DIR__.'/auth.php';
