<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\DashboardController;              // 👈 add this
use App\Http\Controllers\User\BuyerPreferenceController;
use App\Http\Controllers\User\SellerListingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// 🔹 Dashboard view – now using controller so Blade gets data
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile edit (existing)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 🔹 Complete profile (modal / page)
    Route::get('/profile/complete', [ProfileController::class, 'showComplete'])
        ->name('profile.complete');

    Route::post('/profile/complete', [ProfileController::class, 'updateComplete'])
        ->name('profile.complete.update');

    // 🔹 BUYER PREFERENCES ROUTES
    Route::post('/buyer/preferences/land', [BuyerPreferenceController::class, 'storeLand'])
        ->name('buyer.preferences.land.store');

    Route::post('/buyer/preferences/building', [BuyerPreferenceController::class, 'storeBuilding'])
        ->name('buyer.preferences.building.store');

    Route::post('/buyer/preferences/investment', [BuyerPreferenceController::class, 'storeInvestment'])
        ->name('buyer.preferences.investment.store');

        Route::patch(
    '/buyer/preferences/land/{land}/status',
    [BuyerPreferenceController::class, 'updateLandStatus']
)->name('buyer.preferences.land.status');

Route::patch(
    '/buyer/preferences/building/{building}/status',
    [BuyerPreferenceController::class, 'updateBuildingStatus']
)->name('buyer.preferences.building.status');

Route::patch(
    '/buyer/preferences/investment/{investment}/status',
    [BuyerPreferenceController::class, 'updateInvestmentStatus']
)->name('buyer.preferences.investment.status');

Route::patch(
    '/buyer/preferences/land/{land}',
    [BuyerPreferenceController::class, 'updateLand']
)->name('buyer.preferences.land.update');
// web.php
Route::patch(
    '/buyer/preferences/building/{building}',
    [BuyerPreferenceController::class, 'updateBuilding']
)->name('buyer.preferences.building.update');

// web.php
Route::patch(
    '/buyer/preferences/investment/{investment}',
    [BuyerPreferenceController::class, 'updateInvestment']
)->name('buyer.preferences.investment.update');





    // 🔹 SELLER LISTING ROUTES
    Route::post('/seller/land', [SellerListingController::class, 'storeLand'])
        ->name('seller.land.store');

    Route::post('/seller/building', [SellerListingController::class, 'storeBuilding'])
        ->name('seller.building.store');

    Route::post('/seller/investment', [SellerListingController::class, 'storeInvestment'])
        ->name('seller.investment.store');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';