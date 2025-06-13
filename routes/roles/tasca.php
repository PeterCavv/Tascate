<?php


use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TascaController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\TascaAccessMiddleware;


// Tascas Routes

Route::get('/tascas', [TascaController::class, 'index'])->name('tascas.index');
Route::get('/tascas/favorites', [TascaController::class, 'favoriteIndex'])->name('tascas.favorites')->middleware('auth');
Route::get('/tascas/{tasca}', [TascaController::class, 'show'])->name('tascas.show');
Route::get('/tascas/{tasca}/edit', [TascaController::class, 'edit'])->name('tascas.edit')->middleware(['auth', TascaAccessMiddleware::class]);
Route::post('/tascas/{tasca}', [TascaController::class, 'update'])->name('tascas.update')->middleware(['auth', TascaAccessMiddleware::class]);
Route::post('/tascas/{tasca}/toggle-favorite', [TascaController::class, 'toggleFavorite'])->name('tascas.toggle-favorite')->middleware('auth');
Route::get('/{tasca}/map-set',[TascaController::class, 'editTascaLocation'])->name('tascas.map-set')->middleware(['auth', TascaAccessMiddleware::class]);
Route::post('/{tasca}/map-set', [TascaController::class, 'setTascaLocation'])->name('update.map')->middleware(['auth', TascaAccessMiddleware::class]);

Route::get('/gestion', [TascaController::class, 'gestion'])->name('tascas.gestion')->middleware(['auth', TascaAccessMiddleware::class]);

// Reservations Routes

Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index')->middleware('auth');
Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store')->middleware('auth');
Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy')->middleware('auth');
Route::put('/reservations/{reservation}', [ReservationController::class, 'update'])->name('reservations.update')->middleware('auth');
