<?php
use App\Http\Controllers\ReviewController;
use App\Http\Middleware\OnlyCommentUserMiddleware;
use App\Http\Middleware\OnlyPostUserMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostCommentController;


Route::get('/posts', [PostController::class, 'index'])->name('posts.index')->middleware('auth');
Route::get('/posts/create-post', [PostController::class, 'create'])->name('posts.create')->middleware('auth');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store')->middleware('auth');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show')->middleware('auth');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show')->middleware('auth');
Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit')->middleware(['auth', OnlyPostUserMiddleware::class]);
Route::patch('/posts/{post}', [PostController::class, 'update'])->name('posts.update')->middleware(['auth', OnlyPostUserMiddleware::class]);
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy')->middleware(['auth', OnlyPostUserMiddleware::class]);
Route::post('/posts/{post}/toggle-like', [PostController::class, 'toggleLike'])->name('posts.toggle-like')->middleware('auth');
Route::get('/liked-posts', [PostController::class, 'likedPosts'])->name('posts.liked')->middleware('auth');
Route::put('/posts/{comment}/edit', [PostCommentController::class, 'update'])->name('posts.comment.update')->middleware(['auth', OnlyCommentUserMiddleware::class]);
Route::delete('/posts/{comment}/delete', [PostCommentController::class, 'destroy'])->name('posts.comment.delete')->middleware(['auth', OnlyCommentUserMiddleware::class]);
Route::post('/posts/comment/{comment}/response', [PostCommentController::class, 'response'])->name('posts.comment.response')->middleware('auth');


//  Review Routes

Route::get('/users/{user}/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::get('/tascas/{tasca}/reviews/create', [ReviewController::class, 'create'])->name('reviews.create')->middleware('auth');
Route::post('/tascas/{tasca}/reviews', [ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');
Route::put('/tascas/{tasca}/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update')->middleware('auth');
Route::delete('/tascas/{tasca}/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy')->middleware('auth');
