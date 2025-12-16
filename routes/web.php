<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\BuyerPreferenceController;
use App\Http\Controllers\User\SellerListingController;

/*
|--------------------------------------------------------------------------
| FRONTEND (Public Pages)
|--------------------------------------------------------------------------
| Blade files should be in: resources/views/frontend/
| Example: resources/views/frontend/index.blade.php  => view('frontend.index')
*/
Route::get('/', [FrontendController::class, 'index'])->name('front.home');

// Add more public pages if you have them
Route::get('/about',  [FrontendController::class, 'about'])->name('front.about');
Route::get('/contact',[FrontendController::class, 'contact'])->name('front.contact');


/*
|--------------------------------------------------------------------------
| USER DASHBOARD (Auth)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {

    /* ===================== PROFILE ===================== */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Complete profile (modal / page)
    Route::get('/profile/complete', [ProfileController::class, 'showComplete'])->name('profile.complete');
    Route::post('/profile/complete', [ProfileController::class, 'updateComplete'])->name('profile.complete.update');


    /* ===================== BUYER PREFERENCES ===================== */
    Route::prefix('buyer/preferences')->name('buyer.preferences.')->group(function () {

        // Create / store
        Route::post('/land', [BuyerPreferenceController::class, 'storeLand'])->name('land.store');
        Route::post('/building', [BuyerPreferenceController::class, 'storeBuilding'])->name('building.store');
        Route::post('/investment', [BuyerPreferenceController::class, 'storeInvestment'])->name('investment.store');

        // Status updates
        Route::patch('/land/{land}/status', [BuyerPreferenceController::class, 'updateLandStatus'])->name('land.status');
        Route::patch('/building/{building}/status', [BuyerPreferenceController::class, 'updateBuildingStatus'])->name('building.status');
        Route::patch('/investment/{investment}/status', [BuyerPreferenceController::class, 'updateInvestmentStatus'])->name('investment.status');

        // Full details update
        Route::patch('/land/{land}', [BuyerPreferenceController::class, 'updateLand'])->name('land.update');
        Route::patch('/building/{building}', [BuyerPreferenceController::class, 'updateBuilding'])->name('building.update');
        Route::patch('/investment/{investment}', [BuyerPreferenceController::class, 'updateInvestment'])->name('investment.update');
    });


    /* ===================== SELLER LISTINGS ===================== */
    Route::prefix('seller')->name('seller.')->group(function () {

        // Create / store listings
        Route::post('/land', [SellerListingController::class, 'storeLand'])->name('land.store');
        Route::post('/building', [SellerListingController::class, 'storeBuilding'])->name('building.store');
        Route::post('/investment', [SellerListingController::class, 'storeInvestment'])->name('investment.store');

        // Status-only updates
        Route::patch('/land/{id}/status', [SellerListingController::class, 'updateLandStatus'])->name('land.status.update');
        Route::patch('/building/{id}/status', [SellerListingController::class, 'updateBuildingStatus'])->name('building.status.update');
        Route::patch('/investment/{id}/status', [SellerListingController::class, 'updateInvestmentStatus'])->name('investment.status.update');

        // Full details update
        Route::patch('/land/{id}', [SellerListingController::class, 'updateLand'])->name('land.update');
        Route::patch('/building/{id}', [SellerListingController::class, 'updateBuilding'])->name('building.update');
        Route::patch('/investment/{id}', [SellerListingController::class, 'updateInvestment'])->name('investment.update');
    });

});

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
