<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FavoriteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\ReviewController;
use App\Models\Advertisement;
use App\Http\Controllers\ReturnController;
use App\Http\Middleware\RoleCheck;

Route::get('/', [AdvertisementController::class, 'index'])->name('home');
Route::get('/advertisements/upload', function () {
    return view('advertisements.upload');
})->name('advertisements.upload.page');
Route::post('advertisements/upload/csv', [AdvertisementController::class, 'uploadCsv'])->name('advertisements.upload.csv');

Route::middleware(['auth'])->group(function () {
    Route::get('/advertisements/history',[AdvertisementController::class, 'history'])->name('advertisements.history');
    Route::get('/advertisements/agenda', [AdvertisementController::class, 'agenda'])->name('advertisements.agenda');
    Route::get('/advertisements/create', [AdvertisementController::class, 'create'])->name('advertisements.create');
    Route::post('/advertisements', [AdvertisementController::class, 'store'])->name('advertisements.store');
    Route::get('/advertisements/{id}', [AdvertisementController::class, 'info'])->name('advertisements.info');
    Route::get('/advertisements/{advertisement}/edit', [AdvertisementController::class, 'edit'])->name('advertisements.edit');
    Route::put('/advertisements/{advertisement}', [AdvertisementController::class, 'update'])->name('advertisements.update');
    Route::delete('/advertisements/{advertisement}', [AdvertisementController::class, 'destroy'])->name('advertisements.destroy');
    Route::post('/advertisements/{advertisement}',[AdvertisementController::class, 'buy'])->name('advertisements.buy'); 
    Route::post('/advertisements/{advertisement}/rent',[AdvertisementController::class, 'rent'])->name('advertisements.rent');
    Route::post('/advertisements/{advertisement}/bidding',[AdvertisementController::class, 'bidding'])->name('advertisements.bidding');
    Route::get('/advertisements/upload', [AdvertisementController::class, 'showUploadForm'])->name('advertisements.upload.form');

});

Route::middleware(['auth'])->group(function () {
    Route::middleware([RoleCheck::class . ':particulier_adverteerder,zakelijke_adverteerder'])->group(function () {
    Route::get('/advertisements/create', [AdvertisementController::class, 'create'])->name('advertisements.create');
    Route::post('/advertisements', [AdvertisementController::class, 'store'])->name('advertisements.store');
    Route::get('/advertisements/{advertisement}/edit', [AdvertisementController::class, 'edit'])->name('advertisements.edit');
    Route::put('/advertisements/{advertisement}', [AdvertisementController::class, 'update'])->name('advertisements.update');
    Route::delete('/advertisements/{advertisement}', [AdvertisementController::class, 'destroy'])->name('advertisements.destroy');
    });
});



Route::middleware(['auth'])->group(function () {
    Route::post('/reviews/{id}', [ReviewController::class, 'store'])->name('reviews.store');
});
Route::post('/returns/{id}', [ReturnController::class, 'store'])->middleware('auth')->name('returns.store');
Route::get('/returns', [ReturnController::class, 'index'])->middleware('auth')->name('returns.index');
Route::post('/returns/{id}/approve', [ReturnController::class, 'approve'])->middleware('auth')->name('returns.approve');
Route::post('/returns/{id}/reject', [ReturnController::class, 'reject'])->middleware('auth')->name('returns.reject');

Route::get('/dashboard', function () {
    $advertisements = Advertisement::where('user_id', auth()->id())->latest()->get();
    return view('dashboard', compact('advertisements'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/advertisements/{id}/favorite', [FavoriteController::class, 'toggleFavorite'])->middleware('auth')->name('advertisements.favorite');
Route::get('/', [AdvertisementController::class, 'index'])->name('homepage');
// Route::get('/dashboard', [AdvertisementController::class, 'dashboard'])->name('dashboard');
Route::get('/favorites', [AdvertisementController::class, 'favorites'])->name('favorites');
require __DIR__.'/auth.php';
