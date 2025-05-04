<?php

use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Customer\FavController;
use App\Http\Controllers\Customer\ReservationController as CustomerReservationController;
use App\Http\Controllers\Customer\ReviewController as CustomerReviewController;
use \App\Http\Controllers\public\TascaController as PublicTascaController;
use \App\Http\Controllers\common\PostController;
use \App\Http\Controllers\common\LikeController;
use \App\Http\Controllers\common\CommentController;
use \App\Http\Controllers\common\PictureController;
use \App\Http\Controllers\tasca\TascaController;
use Illuminate\Support\Facades\Route;

/**
 * Public routes
 * Everyone can access these routes.
 */
Route::prefix('public')->group(function () {
    Route::apiResource('tascas', PublicTascaController::class);
});

/**
 * Common routes
 * Only a logged in user can access these routes.
 */
Route::prefix('common')->group(function () {
    Route::apiResource('posts', PostController::class);
    
    // Likes
    Route::post('posts/{post}/like', [LikeController::class, 'store']);
    Route::delete('posts/{post}/like', [LikeController::class, 'destroy']);
    
    // Comments
    Route::apiResource('posts.comments', CommentController::class);
    
    // Pictures
    Route::apiResource('posts.pictures', PictureController::class)->except(['update']);
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
