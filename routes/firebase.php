<?php

use App\Http\Controllers\FirebaseMessagingController;
use App\Http\Controllers\Api\FcmTokenController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Firebase Messaging API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Firebase Messaging API routes for your application.
| These routes are typically stateless and meant for API access.
|
*/

Route::middleware('api')->prefix('firebase')->group(function () {
    Route::post('/send-to-device', [FirebaseMessagingController::class, 'sendToDevice']);
    Route::post('/send-to-topic', [FirebaseMessagingController::class, 'sendToTopic']);
    Route::post('/subscribe-to-topic', [FirebaseMessagingController::class, 'subscribeToTopic']);
    Route::post('/unsubscribe-from-topic', [FirebaseMessagingController::class, 'unsubscribeFromTopic']);
});

Route::middleware('auth:sanctum')->prefix('fcm-tokens')->group(function () {
    Route::post('/register', [FcmTokenController::class, 'registerToken']);
    Route::post('/remove', [FcmTokenController::class, 'removeToken']);
    Route::get('/user', [FcmTokenController::class, 'getUserTokens']);
});