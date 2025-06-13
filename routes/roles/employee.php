<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Middleware\EmployeeModifyMiddleware;

Route::middleware(['auth'])->group(function () {
    Route::get('/employees', [EmployeeController::class, 'index'])
        ->name('employees.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])
        ->name('employees.create');
    Route::post('/employees', [EmployeeController::class, 'store'])
        ->name('employees.store');
    Route::get('/employees/{employee}', [EmployeeController::class, 'show'])
        ->name('employees.show');
    Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])
        ->name('employees.edit')->middleware(['auth', EmployeeModifyMiddleware::class]);
    Route::post('/employees/{employee}', [EmployeeController::class, 'update'])
        ->name('employees.update')->middleware(['auth', EmployeeModifyMiddleware::class]);
    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])
        ->name('employees.destroy')->middleware(['auth', EmployeeModifyMiddleware::class]);
    Route::post('/employees/{employee}/promote', [EmployeeController::class, 'promote'])
        ->name('employees.promote')->middleware(['auth', EmployeeModifyMiddleware::class]);
});
