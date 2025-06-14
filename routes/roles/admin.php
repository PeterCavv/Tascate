<?php
use App\Http\Controllers\TascaProposalController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminAccessMiddleware;
use App\Http\Controllers\ManagerController;


Route::middleware( AdminAccessMiddleware::class)->group(function () {
    Route::get('/managers', [ManagerController::class, 'index'])
        ->name('managers.index');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/tascas-proposals', [TascaProposalController::class, 'index'])->name('tascas-proposals.index')->middleware('auth');
    Route::get('/tascas-proposals/{tascaProposal}', [TascaProposalController::class, 'show'])->name('tascas-proposals.show')->middleware('auth');
    Route::put('/tascas-proposals/{tascaProposal}', [TascaProposalController::class, 'update'])->name('tascas-proposals.update')->middleware('auth');
    Route::post('/tascas-proposals/{tascaProposal}/approve', [TascaProposalController::class, 'approve'])->name('tascas-proposals.approve')->middleware('auth');
    Route::post('/tascas-proposals/{tascaProposal}/clone', [TascaProposalController::class, 'clone'])->name('tascas-proposals.clone')->middleware('auth');
});
