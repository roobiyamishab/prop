<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminBuyerPreferenceController;
use App\Http\Controllers\Admin\AdminSellerListingController;
use App\Http\Controllers\Admin\BuyerPropertyController;

/*
|--------------------------------------------------------------------------
| Admin Auth (Guest)
|--------------------------------------------------------------------------
*/
Route::middleware('guest:admin')->group(function () {
    Route::get('/admin/login', [LoginController::class, 'showLoginForm'])
        ->name('admin.login');

    Route::post('/admin/login', [LoginController::class, 'login'])
        ->name('admin.login.submit');
});

/*
|--------------------------------------------------------------------------
| Admin Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth:admin')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // ----------------------------------------------------
        // 🔹 Dashboard (NOW USING CONTROLLER)
        // ----------------------------------------------------
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // 🔹 Logout
        Route::post('/logout', [LoginController::class, 'logout'])
            ->name('logout');

        // ----------------------------------------------------
        // 🔹 BASIC USER PROFILE UPDATE (ADMIN SIDE)
        // ----------------------------------------------------
        Route::post('/users/{user}/profile-basic', [AdminUserController::class, 'updateBasicProfile'])
            ->name('users.profile.basic.update');

        // ----------------------------------------------------
        // 🔹 BUYER PROPERTIES OVERVIEW (ALL PROPERTIES PAGE)
        // ----------------------------------------------------
        Route::get('/buyers/{buyer}/properties', [BuyerPropertyController::class, 'index'])
            ->name('buyer.properties.index');  // => admin.buyer.properties.index

        // ----------------------------------------------------
        // SUPER ADMIN – BUYER PREFERENCES (LAND / BUILDING / INVESTMENT)
        // ----------------------------------------------------

        // (1) Create/store preferences for a specific user
        Route::prefix('buyers/preferences')
            ->name('buyer.preferences.')
            ->group(function () {

                Route::post('/land', [AdminBuyerPreferenceController::class, 'storeLand'])
                    ->name('land.store');   // => admin.buyer.preferences.land.store

                Route::post('/building', [AdminBuyerPreferenceController::class, 'storeBuilding'])
                    ->name('building.store');

                Route::post('/investment', [AdminBuyerPreferenceController::class, 'storeInvestment'])
                    ->name('investment.store');
            });

        // (2) Status change + full update + DETAILED VIEW + DELETE by model ID
        Route::prefix('buyer/preferences')
            ->name('buyers.preferences.')
            ->group(function () {

                // 🔹 Status update routes
                Route::patch('/land/{land}/status', [AdminBuyerPreferenceController::class, 'updateLandStatus'])
                    ->name('land.status');

                Route::patch('/building/{building}/status', [AdminBuyerPreferenceController::class, 'updateBuildingStatus'])
                    ->name('building.status');

                Route::patch('/investment/{investment}/status', [AdminBuyerPreferenceController::class, 'updateInvestmentStatus'])
                    ->name('investment.status');

                // 🔹 Full update routes
                Route::patch('/land/{land}', [AdminBuyerPreferenceController::class, 'updateLand'])
                    ->name('land.update');

                Route::patch('/building/{building}', [AdminBuyerPreferenceController::class, 'updateBuilding'])
                    ->name('building.update');

                Route::patch('/investment/{investment}', [AdminBuyerPreferenceController::class, 'updateInvestment'])
                    ->name('investment.update');

                // 🔹 DELETE routes (NEW)
                Route::delete('/land/{land}', [AdminBuyerPreferenceController::class, 'destroyLand'])
                    ->name('land.destroy');

                Route::delete('/building/{building}', [AdminBuyerPreferenceController::class, 'destroyBuilding'])
                    ->name('building.destroy');

                Route::delete('/investment/{investment}', [AdminBuyerPreferenceController::class, 'destroyInvestment'])
                    ->name('investment.destroy');

                // 🔹 Detailed View routes
                Route::get('/land/{land}', [AdminBuyerPreferenceController::class, 'showLand'])
                    ->name('land.show');        // admin.buyers.preferences.land.show

                Route::get('/building/{building}', [AdminBuyerPreferenceController::class, 'showBuilding'])
                    ->name('building.show');    // admin.buyers.preferences.building.show

                Route::get('/investment/{investment}', [AdminBuyerPreferenceController::class, 'showInvestment'])
                    ->name('investment.show');  // admin.buyers.preferences.investment.show
            });

        // ----------------------------------------------------
        // SUPER ADMIN – SELLER LISTINGS
        // ----------------------------------------------------

        // Create seller listings for a specific user
        Route::prefix('seller/{user}')
            ->name('seller.')
            ->group(function () {

                Route::post('/land', [AdminSellerListingController::class, 'storeLand'])
                    ->name('land.store');

                Route::post('/building', [AdminSellerListingController::class, 'storeBuilding'])
                    ->name('building.store');

                Route::post('/investment', [AdminSellerListingController::class, 'storeInvestment'])
                    ->name('investment.store');
            });

        // Update + status update for existing listings
        Route::prefix('seller')
            ->name('seller.')
            ->group(function () {

                Route::patch('/land/{land}/status', [AdminSellerListingController::class, 'updateLandStatus'])
                    ->name('land.status.update');

                Route::patch('/building/{building}/status', [AdminSellerListingController::class, 'updateBuildingStatus'])
                    ->name('building.status.update');

                Route::patch('/investment/{investment}/status', [AdminSellerListingController::class, 'updateInvestmentStatus'])
                    ->name('investment.status.update');

                Route::patch('/land/{land}', [AdminSellerListingController::class, 'updateLand'])
                    ->name('land.update');

                Route::patch('/building/{building}', [AdminSellerListingController::class, 'updateBuilding'])
                    ->name('building.update');

                Route::patch('/investment/{investment}', [AdminSellerListingController::class, 'updateInvestment'])
                    ->name('investment.update');
            });
    });
