<?php

use App\Http\Controllers\Api\Event\EventController;
use App\Http\Controllers\Api\Matrimonial\MatrimonialProfileController;
use App\Http\Controllers\Api\Matrimonial\MatrimonialImageController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\Settings\SettingsController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PublicationController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/login', [AuthController::class, 'login']);
// Protected routes

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // User routes - explicit routes to avoid conflicts
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/search', [UserController::class, 'search']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::get('/users/matrimonial-candidates/{gender}', [UserController::class, 'matrimonialCandidates']);
    
    // Publication routes
    Route::get('/publications', [PublicationController::class, 'index']);
    Route::get('/publications/search', [PublicationController::class, 'search']);
    Route::post('/publications', [PublicationController::class, 'store']);
    Route::get('/publications/{publication}', [PublicationController::class, 'show']);
    Route::put('/publications/{publication}', [PublicationController::class, 'update']);
    Route::delete('/publications/{publication}', [PublicationController::class, 'destroy']);
    
    // News routes
    Route::apiResource('news', NewsController::class);
    Route::post('/news/{news}/image', [NewsController::class, 'uploadImage']);
    Route::delete('/news/{news}/image/{mediaId}', [NewsController::class, 'deleteImage']);
    
    // Event routes
    Route::apiResource('events', EventController::class);
    Route::get('/events/search', [EventController::class, 'search']);
    
    // Matrimonial routes
    Route::apiResource('matrimonial-profiles', MatrimonialProfileController::class);
    Route::get('/matrimonial-profiles/by-gender/{gender}', [MatrimonialProfileController::class, 'byGender']);
    Route::post('/matrimonial-profiles/{matrimonialProfile}/images', [MatrimonialImageController::class, 'uploadImages']);
    Route::delete('/matrimonial-profiles/{matrimonialProfile}/images/{mediaId}', [MatrimonialImageController::class, 'deleteImage']);
    
    // Settings routes
    Route::get('/settings/payment-gateway', [SettingsController::class, 'paymentGateway']);
    Route::get('/settings/all', [SettingsController::class, 'all']);
});

// Include Firebase routes
require __DIR__.'/firebase.php';