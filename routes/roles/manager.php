<?php

use App\Http\Middleware\TascaAdminOnlyManagerMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManagerController;
use App\Http\Middleware\ManagerAccessMiddleware;
use App\Http\Middleware\ManagerModifyMiddleware;
use App\Http\Middleware\TascaAccessMiddleware;

// Manager routes
Route::middleware(['auth'])->group(function () {
//    Route::get('/managers/create', [ManagerController::class, 'create'])
//        ->name('managers.create');
//    Route::post('/managers', [ManagerController::class, 'store'])
//        ->name('managers.store');
    Route::get('/managers/{manager}', [ManagerController::class, 'show'])
        ->name('managers.show')->middleware(['auth', ManagerAccessMiddleware::class]);
    Route::get('/managers/{manager}/edit', [ManagerController::class, 'edit'])
        ->name('managers.edit')->middleware(['auth', ManagerModifyMiddleware::class]);
    Route::put('/managers/{manager}', [ManagerController::class, 'update'])
        ->name('managers.update')->middleware(['auth', ManagerModifyMiddleware::class]);
    Route::delete('/managers/{manager}', [ManagerController::class, 'destroy'])
        ->name('managers.destroy')->middleware(['auth', ManagerModifyMiddleware::class]);
    Route::post('/managers/{manager}/demote', [ManagerController::class, 'demote'])
        ->name('managers.demote')->middleware(['auth', ManagerModifyMiddleware::class]);

    Route::post('/managers/{manager}/toggle-permission', [ManagerController::class, 'togglePermission'])
        ->name('managers.toggle-permission')->middleware(['auth', TascaAdminOnlyManagerMiddleware::class]);
});

