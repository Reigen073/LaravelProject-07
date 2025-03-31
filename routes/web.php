<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdvertisementController;
use App\Models\Advertisement;

Route::get('/', [AdvertisementController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/advertisements/create', [AdvertisementController::class, 'create'])->name('advertisements.create');
    Route::post('/advertisements', [AdvertisementController::class, 'store'])->name('advertisements.store');
    Route::get('/advertisements/{id}', [AdvertisementController::class, 'info'])->name('advertisements.info');
    Route::get('/dashboard', [AdvertisementController::class, 'userAdvertisements'])->name('user.advertisements');
    Route::get('/advertisements/{advertisement}/edit', [AdvertisementController::class, 'edit'])->name('advertisements.edit');
    Route::put('/advertisements/{advertisement}', [AdvertisementController::class, 'update'])->name('advertisements.update');
    Route::delete('/advertisements/{advertisement}', [AdvertisementController::class, 'destroy'])->name('advertisements.destroy');
});

Route::get('/dashboard', function () {
    $advertisements = Advertisement::where('user_id', auth()->id())->latest()->get();
    return view('dashboard', compact('advertisements'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

require __DIR__.'/auth.php';
