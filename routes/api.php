<?php

use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Customer\ReservationController as CustomerReservationController;
use \App\Http\Controllers\public\TascaController;
use Illuminate\Support\Facades\Route;

Route::prefix('public')->group(function () {
    Route::apiResource('tascas', TascaController::class);
});

Route::prefix('customer')->group(function () {
    Route::apiResource('reservations', CustomerReservationController::class);
});

Route::prefix('admin')->group(function () {
    Route::apiResource('reservations', AdminReservationController::class);
});
