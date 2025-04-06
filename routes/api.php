<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdvertisementAPIController;
use App\Http\Controllers\Api\AuthController;
use App\Models\Advertisement;
use App\Models\User;

    Route::get('advertisements', [AdvertisementAPIController::class, 'index']);
    Route::post('login', function (Request $request) {
        $user = User::where('email', $request->email)->first();
        if (!$user || !\Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        $token = $user->createToken('API Token')->plainTextToken;
        $advertisements = Advertisement::where('user_id', $user->id)->get();
        return response()->json([
            'token' => $token,
            'advertisements' => $advertisements
        ]);
    });
    
    
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });
    
Route::middleware('auth:sanctum')->prefix('advertisements')->group(function () {
    Route::get('{id}', [AdvertisementAPIController::class, 'show']);
    Route::post('/', [AdvertisementAPIController::class, 'store']);
    Route::put('{id}', [AdvertisementAPIController::class, 'update']);
    Route::delete('{id}', [AdvertisementAPIController::class, 'destroy']);
});


