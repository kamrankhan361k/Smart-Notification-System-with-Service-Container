<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('notifications')->group(function () {
    Route::get('/demo', [NotificationController::class, 'showDemo'])->name('notifications.demo');
    Route::get('/channels', [NotificationController::class, 'getChannels'])->name('notifications.channels');
    Route::post('/send', [NotificationController::class, 'sendNotification'])->name('notifications.send');
    Route::get('/test', [NotificationController::class, 'testChannels'])->name('notifications.test');
});

// API routes
Route::prefix('api/notifications')->group(function () {
    Route::get('/channels', [NotificationController::class, 'getChannels']);
    Route::post('/send', [NotificationController::class, 'sendNotification']);
});
