<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FavoriteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReturnController;
use App\Http\Middleware\RoleCheck;
use App\Models\Advertisement;
use App\Http\Controllers\DashboardSettingsController;
use App\Models\CustomLink;
use App\Http\Controllers\CustomLinkController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\Auth\LoginController;
use App\Models\Contract;


Route::get('/', [AdvertisementController::class, 'index'])->name('homepage');
Route::middleware(['auth'])->group(function () {
    Route::post('/dashboard/settings/store', [DashboardSettingsController::class, 'store'])->name('dashboard.settings.store');
    Route::get('/dashboard/settings/fetch', [DashboardSettingsController::class, 'fetch'])->name('dashboard.settings.fetch');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/advertisements/history', [AdvertisementController::class, 'history'])->name('advertisements.history');
    Route::get('/advertisements/agenda', [AdvertisementController::class, 'agenda'])->name('advertisements.agenda');
    Route::post('/advertisements/{advertisement}/buy/{advertisement2?}', [AdvertisementController::class, 'buy'])->name('advertisements.buy');
    Route::post('/advertisements/{advertisement}/rent', [AdvertisementController::class, 'rent'])->name('advertisements.rent');
    Route::post('/advertisements/{advertisement}/bidding', [AdvertisementController::class, 'bidding'])->name('advertisements.bidding');
    
    Route::post('/reviews/{id}', [ReviewController::class, 'store'])->name('reviews.store');
    
    Route::prefix('returns')->group(function () {
        Route::get('/', [ReturnController::class, 'index'])->name('returns.index');
        Route::post('/{id}', [ReturnController::class, 'store'])->name('returns.store');
        Route::post('/{id}/approve', [ReturnController::class, 'approve'])->name('returns.approve');
        Route::post('/{id}/reject', [ReturnController::class, 'reject'])->name('returns.reject');
    });

    Route::middleware([RoleCheck::class . ':particulier_adverteerder,zakelijke_adverteerder','admin'])->group(function () {
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

    Route::get('/dashboard', function () {
        $advertisements = Advertisement::where('user_id', auth()->id())->latest()->get();
        $contracts = Contract::where('user_id', auth()->id())->get();  // Fetch contracts for the authenticated user
        return view('dashboard', compact('advertisements', 'contracts'));  // Pass contracts to the view
    })->middleware('verified')->name('dashboard');
});

Route::get('/advertisements/upload', [AdvertisementController::class, 'showUploadForm'])->name('advertisements.upload.form');
Route::post('/advertisements/upload/csv', [AdvertisementController::class, 'uploadCsv'])->name('advertisements.upload.csv');
Route::get('/advertisements/{id}', [AdvertisementController::class, 'info'])->name('advertisements.info');
Route::get('/favorites', [AdvertisementController::class, 'favorites'])->name('favorites');
Route::get('/contracts/{contract}/export', [ContractController::class, 'export'])->name('contracts.export');

Route::prefix('login')->group(function () {
    Route::get('/', [LoginController::class, 'show'])->name('register');
    Route::post('/', [LoginController::class, 'register']);
});
Route::prefix('register')->group(function () {
    Route::get('/', [RegisterController::class, 'show'])->name('register');
    Route::post('/', [RegisterController::class, 'register']);
});
Route::post('/custom-link', function (Request $request) {
    // Validate the input directly on the $request object
    $request->validate([
        'link_name' => 'required|string|max:255',
    ]);

    // Save the custom link name
    CustomLink::create([
        'link_name' => $request->input('link_name'),
    ]);

    // Redirect back with success message
    return back()->with('success', 'Custom link name saved!');
    })->name('custom-link.store');
    Route::get('/landing', [LandingController::class, 'index'])->name('landing.page');
    Route::get('/{link_name}', function ($link_name) {
        // Hier controleer je of de link bestaat
        $customLink = CustomLink::where('link_name', $link_name)->first();

        // Als de link niet bestaat, toon een 404-pagina
        if (!$customLink) {
            return abort(404, 'Pagina niet gevonden');
        }

        // Controleer of de gebruiker is ingelogd (optioneel, afhankelijk van je vereisten)
        if (Auth::check()) {
            // Als de gebruiker is ingelogd, laat ze het dashboard zien
            return redirect()->route('dashboard');
        }

        // Als de gebruiker niet is ingelogd, kun je ze naar een andere pagina sturen
        return redirect()->route('login');
    });
    Route::post('/custom-link', [CustomLinkController::class, 'store'])->name('custom-link.store');

    Route::middleware(['auth'])->group(function () {
        Route::get('/admin/contracts', [ContractController::class, 'index'])->name('contracts.index');
        Route::post('/admin/contracts/upload', [ContractController::class, 'upload'])->name('contracts.upload');
    });
    
require __DIR__.'/auth.php';
