<?php

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::post('register', RegisterController::class);
Route::group(['prefix' => 'subscriptions'], function () {
    Route::post('purchase', [SubscriptionController::class, 'store']);
    Route::post('check-status', [SubscriptionController::class, 'show']);
});
