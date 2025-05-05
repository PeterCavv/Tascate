<?php

use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Customer\FavController;
use App\Http\Controllers\Customer\ReservationController as CustomerReservationController;
use App\Http\Controllers\Customer\ReviewController as CustomerReviewController;
use \App\Http\Controllers\public\TascaController as PublicTascaController;
use \App\Http\Controllers\tasca\TascaController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Users\UserController;


/**
 * Public routes
 * Everyone can access these routes.
 */
Route::prefix('public')->group(function () {
    Route::apiResource('tascas', PublicTascaController::class);
});

/**
 * Tasca routes
 * Only a User Tasca (or Admin) can update their own data.
 */
Route::prefix('tasca')->group(function () {
    Route::apiResource('tascas', TascaController::class)->only(['update']);
});

/**
 * Customer routes
 * Only a User Customer (or Admin) can update their own data.
 */
Route::prefix('customer')->group(function () {
    Route::apiResource('reservations', CustomerReservationController::class);
    Route::apiResource('reviews', CustomerReviewController::class);

    Route::addRoute(['POST', 'DELETE'], 'favs', [FavController::class, 'addOrRemoveFav']);
});

/**
 * Admin routes
 * Only an Admin can access these routes.
 */
Route::prefix('admin')->group(function () {
    Route::apiResource('reservations', AdminReservationController::class);
    Route::apiResource('reviews', AdminReviewController::class);
});

/**
 * Register User Route
 * Route for Registering a new user.
 */


Route::post('register', [UserController::class, 'store'])
    ->name('register');

/**
 * Login User Route
 * Route for logging in a user.
 */

Route::post('login', [UserController::class, 'login'])
    ->name('login');

/**
 * User Routes
 * Routes for Users
 */
Route::get('users', [UserController::class, 'index'])
    ->name('users.index');

Route::get('users/{user}', [UserController::class, 'show'])
    ->name('users.show');

Route::patch('users/{user}', [UserController::class, 'update'])
    ->name('users.update')->middleware('auth:sanctum'); //Añadir police para que solo el usuario pueda actualizar su propio perfil

Route::delete('users/{user}', [UserController::class, 'destroy'])
    ->name('users.destroy')->middleware('auth:sanctum'); //Añadir police para que solo el usuario pueda eliminar su propio perfil
