<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdvertisementAPIController;
use App\Http\Controllers\Api\AuthController;

    Route::get('advertisements', [AdvertisementAPIController::class, 'index']);
    Route::post('login', function (Request $request) {
        $user = \App\Models\User::where('email', $request->email)->first();
    
        // Check if user exists and password is correct
        if (!$user || !\Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    
        // Generate token
        $token = $user->createToken('API Token')->plainTextToken;
    
        // Fetch advertisements for the authenticated user
        $advertisements = \App\Models\Advertisement::where('user_id', $user->id)->get();
    
        // Return token and advertisements in response
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


