<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\BuyerPreferenceController;
use App\Http\Controllers\User\SellerListingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// 🔹 Dashboard view – using controller so Blade gets data
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {

    /* ===================== PROFILE ===================== */
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // Complete profile (modal / page)
    Route::get('/profile/complete', [ProfileController::class, 'showComplete'])
        ->name('profile.complete');

    Route::post('/profile/complete', [ProfileController::class, 'updateComplete'])
        ->name('profile.complete.update');



    /* ===================== BUYER PREFERENCES ===================== */
    Route::prefix('buyer/preferences')
        ->name('buyer.preferences.')
        ->group(function () {

            // Create / store
            Route::post('/land', [BuyerPreferenceController::class, 'storeLand'])
                ->name('land.store');

            Route::post('/building', [BuyerPreferenceController::class, 'storeBuilding'])
                ->name('building.store');

            Route::post('/investment', [BuyerPreferenceController::class, 'storeInvestment'])
                ->name('investment.store');

            // Status updates (quick status modal)
            Route::patch('/land/{land}/status', [BuyerPreferenceController::class, 'updateLandStatus'])
                ->name('land.status');

            Route::patch('/building/{building}/status', [BuyerPreferenceController::class, 'updateBuildingStatus'])
                ->name('building.status');

            Route::patch('/investment/{investment}/status', [BuyerPreferenceController::class, 'updateInvestmentStatus'])
                ->name('investment.status');

            // Full details update (edit modals / forms)
            Route::patch('/land/{land}', [BuyerPreferenceController::class, 'updateLand'])
                ->name('land.update');

            Route::patch('/building/{building}', [BuyerPreferenceController::class, 'updateBuilding'])
                ->name('building.update');

            Route::patch('/investment/{investment}', [BuyerPreferenceController::class, 'updateInvestment'])
                ->name('investment.update');
        });



    /* ===================== SELLER LISTINGS ===================== */
    Route::prefix('seller')
        ->name('seller.')
        ->group(function () {

            // Create / store listings
            Route::post('/land', [SellerListingController::class, 'storeLand'])
                ->name('land.store');

            Route::post('/building', [SellerListingController::class, 'storeBuilding'])
                ->name('building.store');

            Route::post('/investment', [SellerListingController::class, 'storeInvestment'])
                ->name('investment.store');

            // Status-only updates (status modal)
            Route::patch('/land/{id}/status', [SellerListingController::class, 'updateLandStatus'])
                ->name('land.status.update');

            Route::patch('/building/{id}/status', [SellerListingController::class, 'updateBuildingStatus'])
                ->name('building.status.update');

            Route::patch('/investment/{id}/status', [SellerListingController::class, 'updateInvestmentStatus'])
                ->name('investment.status.update');

            // Full details update (your new edit-detail modals)
            Route::patch('/land/{id}', [SellerListingController::class, 'updateLand'])
                ->name('land.update');

            Route::patch('/building/{id}', [SellerListingController::class, 'updateBuilding'])
                ->name('building.update');

            Route::patch('/investment/{id}', [SellerListingController::class, 'updateInvestment'])
                ->name('investment.update');
        });

});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
