<?php

use App\Enums\Role;
use App\Http\Controllers\Admin\ImpersonationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TascaController;
use App\Http\Controllers\TascaProposalController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\WelcomePageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostCommentController;

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
Route::post('/posts/{post}/comment', [PostCommentController::class, 'store'])->name('posts.comment')->middleware('auth');
Route::put('/posts/{comment}/edit', [PostCommentController::class, 'update'])->name('posts.comment.update')->middleware('auth');
Route::delete('/posts/{comment}/delete', [PostCommentController::class, 'destroy'])->name('posts.comment.delete')->middleware('auth');
Route::post('/posts/comment/{comment}/response', [PostCommentController::class, 'response'])->name('posts.comment.response')->middleware('auth');

require __DIR__.'/auth.php';

// Tasca Proposals Routes

Route::get('/register/tascas-proposals', [TascaProposalController::class, 'registerForm'])->name('tascas-proposals.create');
Route::post('/register/tascas-proposals', [TascaProposalController::class, 'store'])->name('tascas-proposals.store');
Route::get('/tascas-proposals', [TascaProposalController::class, 'index'])->name('tascas-proposals.index')->middleware('auth');
Route::get('/tascas-proposals/{tascaProposal}', [TascaProposalController::class, 'show'])->name('tascas-proposals.show')->middleware('auth');
Route::put('/tascas-proposals/{tascaProposal}', [TascaProposalController::class, 'update'])->name('tascas-proposals.update')->middleware('auth');
Route::post('/tascas-proposals/{tascaProposal}/approve', [TascaProposalController::class, 'approve'])->name('tascas-proposals.approve')->middleware('auth');
Route::post('/tascas-proposals/{tascaProposal}/clone', [TascaProposalController::class, 'clone'])->name('tascas-proposals.clone')->middleware('auth');

// Imagenes privadas

Route::get('/imagen-privada/{path}', function (Request $request, $path) {

    if(!auth()->user() && auth()->user()->role !== Role::ADMIN->value)
        abort(403);

    $path = str_replace('..', '', $path);
    if (!Storage::disk('private')->exists($path)) {
        abort(404);
    }

    $file = Storage::disk('private')->get($path);
    $mime = Storage::disk('private')->mimeType($path);

    return Response::make($file, 200)->header("Content-Type", $mime);
})->where('path', '.*')->middleware('auth');

// Tascas Routes

Route::get('/tascas', [TascaController::class, 'index'])->name('tascas.index');
Route::get('/tascas/favorites', [TascaController::class, 'favoriteIndex'])->name('tascas.favorites')->middleware('auth');
Route::get('/tascas/{tasca}', [TascaController::class, 'show'])->name('tascas.show');
Route::get('/tascas/{tasca}/edit', [TascaController::class, 'edit'])->name('tascas.edit')->middleware('auth');
Route::post('/tascas/{tasca}', [TascaController::class, 'update'])->name('tascas.update')->middleware('auth');
Route::post('/tascas/{tasca}/toggle-favorite', [TascaController::class, 'toggleFavorite'])->name('tascas.toggle-favorite')->middleware('auth');
Route::get('/{tasca}/map-set', [TascaController::class, 'editTascaLocation'])->name('set.map');
Route::post('/{tasca}/map-set', [TascaController::class, 'setTascaLocation'])->name('update.map');

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
