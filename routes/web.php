<?php

use App\Http\Controllers\Admin\ImpersonationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TascaController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\WelcomePageController;
use App\Http\Controllers\PostController;

Route::get('/', WelcomePageController::class)->name('welcome');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes

Route::middleware('auth')->group(function () {
    Route::get('/impersonate/stop', [ImpersonationController::class, 'stop'])
        ->name('impersonate.stop');

    Route::get('impersonate/{user}', [ImpersonationController::class, 'start'])
        ->middleware('can:impersonate,user')
        ->name('impersonate.start');
});

//User Routes

Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware('auth');
Route::post('/users/{user}', [UserController::class, 'update'])->name('users.update')->middleware('auth');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('auth');

// Posts Routes

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts', [PostController::class, 'index'])->name('posts.index')->middleware('auth');
Route::get('/posts/create-post', [PostController::class, 'create'])->name('posts.create')->middleware('auth');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store')->middleware('auth');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show')->middleware('auth');
Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit')->middleware('auth');
Route::patch('/posts/{post}', [PostController::class, 'update'])->name('posts.update')->middleware('auth');
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy')->middleware('auth');
Route::post('/posts/{post}/toggle-like', [PostController::class, 'toggleLike'])->name('posts.toggle-like')->middleware('auth');
Route::get('/liked-posts', [PostController::class, 'likedPosts'])->name('posts.liked')->middleware('auth');

require __DIR__.'/auth.php';

// Tascas Routes

Route::get('/tascas', [TascaController::class, 'index'])->name('tascas.index');
Route::get('/tascas/{tasca}', [TascaController::class, 'show'])->name('tascas.show');
Route::get('/tascas/{tasca}/edit', [TascaController::class, 'edit'])->name('tascas.edit')->middleware('auth');
Route::post('/tascas/{tasca}', [TascaController::class, 'update'])->name('tascas.update')->middleware('auth');

// Reservations Routes

Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store')->middleware('auth');
Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy')->middleware('auth');
Route::put('/reservations/{reservation}', [ReservationController::class, 'update'])->name('reservations.update')->middleware('auth');

// Reviews Routes

Route::get('/users/{user}/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::get('/tascas/{tasca}/reviews/create', [ReviewController::class, 'create'])->name('reviews.create')->middleware('auth');
Route::post('/tascas/{tasca}/reviews', [ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');
Route::get('/tascas/{tasca}/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit')->middleware('auth');
Route::put('/tascas/{tasca}/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update')->middleware('auth');
Route::delete('/tascas/{tasca}/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy')->middleware('auth');

// Accesibilidad

Route::get('/accessibility', function () {
    return Inertia::render('Accessibility');
})->name('accessibility');

// RUTAS

require __DIR__.'/roles/admin.php';
require __DIR__.'/roles/manager.php';
require __DIR__.'/roles/employee.php';
require __DIR__.'/roles/tasca.php';
require __DIR__.'/roles/customer.php';


