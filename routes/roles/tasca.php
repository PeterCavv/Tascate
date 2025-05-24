<?php

use App\Http\Controllers\TascaController;
use Illuminate\Support\Facades\Route;

// Tascas Routes

Route::get('/tascas', [TascaController::class, 'index'])->name('tascas.index');
Route::get('/tascas/{tasca}', [TascaController::class, 'show'])->name('tascas.show');
