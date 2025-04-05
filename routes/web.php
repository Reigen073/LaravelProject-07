<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FavoriteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReturnController;
use App\Http\Middleware\RoleCheck;
use App\Models\Advertisement;


Route::get('/', [AdvertisementController::class, 'index'])->name('homepage');

Route::middleware(['auth'])->group(function () {
    Route::get('/advertisements/history', [AdvertisementController::class, 'history'])->name('advertisements.history');
    Route::get('/advertisements/agenda', [AdvertisementController::class, 'agenda'])->name('advertisements.agenda');
    Route::post('/advertisements/{advertisement}/buy/{advertisement2?}', [AdvertisementController::class, 'buy'])->name('advertisements.buy');
    Route::post('/advertisements/{advertisement}/rent', [AdvertisementController::class, 'rent'])->name('advertisements.rent');
    Route::post('/advertisements/{advertisement}/bidding', [AdvertisementController::class, 'bidding'])
    ->name('advertisements.bidding');
    
    Route::post('/reviews/{id}', [ReviewController::class, 'store'])->name('reviews.store');
    
    Route::prefix('returns')->group(function () {
        Route::get('/', [ReturnController::class, 'index'])->name('returns.index');
        Route::post('/{id}', [ReturnController::class, 'store'])->name('returns.store');
        Route::post('/{id}/approve', [ReturnController::class, 'approve'])->name('returns.approve');
        Route::post('/{id}/reject', [ReturnController::class, 'reject'])->name('returns.reject');
    });

    Route::middleware([RoleCheck::class . ':particulier_adverteerder,zakelijke_adverteerder'])->group(function () {
        Route::prefix('advertisements')->group(function () {
            Route::get('/create', [AdvertisementController::class, 'create'])->name('advertisements.create');
            Route::post('/', [AdvertisementController::class, 'store'])->name('advertisements.store');
            Route::get('/{advertisement}/edit', [AdvertisementController::class, 'edit'])->name('advertisements.edit');
            Route::put('/{advertisement}', [AdvertisementController::class, 'update'])->name('advertisements.update');
            Route::delete('/{advertisement}', [AdvertisementController::class, 'destroy'])->name('advertisements.destroy');
            Route::post('/biddingAccept/{id}', [AdvertisementController::class, 'biddingAccept'])->name('advertisements.biddingAccept');
            Route::post('/biddingReject/{id}', [AdvertisementController::class, 'biddingReject'])->name('advertisements.biddingReject');
        });
    });
    
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::get('/{user}', [ProfileController::class, 'show'])->name('profile.show');
    });
    
    Route::post('/advertisements/{id}/favorite', [FavoriteController::class, 'toggleFavorite'])->name('advertisements.favorite');

    Route::get('/dashboard', [AdvertisementController::class, 'dashboard'])->middleware('verified')->name('dashboard');
});

Route::get('/advertisements/upload', [AdvertisementController::class, 'showUploadForm'])->name('advertisements.upload.form');
Route::post('/advertisements/upload/csv', [AdvertisementController::class, 'uploadCsv'])->name('advertisements.upload.csv');
Route::get('/advertisements/{id}', [AdvertisementController::class, 'info'])->name('advertisements.info');
Route::get('/favorites', [AdvertisementController::class, 'favorites'])->name('favorites');

Route::prefix('register')->group(function () {
    Route::get('/', [RegisterController::class, 'show'])->name('register');
    Route::post('/', [RegisterController::class, 'register']);
});

require __DIR__.'/auth.php';
