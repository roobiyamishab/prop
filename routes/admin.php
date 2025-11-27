<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminBuyerPreferenceController;
use App\Http\Controllers\Admin\AdminSellerListingController;
use App\Http\Controllers\Admin\BuyerPropertyController;
use App\Http\Controllers\Admin\SellerLandListingController; // 🔹 add this
use App\Http\Controllers\Admin\SellerBuildingListingController;
use App\Http\Controllers\Admin\SellerInvestmentListingController;
use App\Http\Controllers\Admin\SellerPropertyController;

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
        Route::prefix('seller')
            ->name('seller.')
            ->group(function () {

                // 🔹 CREATE / STORE (no {user} in URI, user_id comes from form)
                Route::post('/land', [SellerLandListingController::class, 'store'])
                    ->name('land.store');          // => admin.seller.land.store

                Route::post('/building', [SellerBuildingListingController::class, 'storeBuilding'])
                    ->name('building.store');      // => admin.seller.building.store

                Route::post('/investment', [SellerInvestmentListingController::class, 'storeInvestment'])
                    ->name('investment.store');    // => admin.seller.investment.store

 Route::get('/land/{land}', [SellerLandListingController::class, 'showLand'])
            ->name('land.show');          // admin.seller.land.show

            Route::get('/land/{land}/edit', [AdminSellerListingController::class, 'editLand'])
                    ->name('land.edit');

                Route::delete('/land/{land}', [SellerlandListingController::class, 'destroyLand'])
                    ->name('land.destroy');

        Route::get('/building/{building}', [SellerBuildingListingController::class, 'showBuilding'])
            ->name('building.show');      // admin.seller.building.show
              Route::get('/building/{building}/edit', [SellerBuildingListingController::class, 'editBuilding'])
                    ->name('building.edit');

                Route::delete('/building/{building}', [SellerBuildingListingController::class, 'destroyBuilding'])
                    ->name('building.destroy');

        Route::get('/investment/{investment}', [SellerInvestmentListingController::class, 'showInvestment'])
            ->name('investment.show');    // admin.seller.investment.show
             Route::get('/investment/{investment}/edit', [SellerInvestmentListingController::class, 'editInvestment'])
                    ->name('investment.edit');

                Route::delete('/investment/{investment}', [SellerInvestmentListingController::class, 'destroyInvestment'])
                    ->name('investment.destroy');




                // 🔹 UPDATE + STATUS routes (if you already have these in AdminSellerListingController)
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


                Route::get('/sellers/{seller}/properties', [SellerPropertyController::class, 'index'])
    ->name('properties.index');

            });
    });
