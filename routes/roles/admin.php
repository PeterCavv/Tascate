<?php
use App\Http\Controllers\TascaProposalController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminAccessMiddleware;


Route::middleware( AdminAccessMiddleware::class)->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/register/tascas-proposals', [TascaProposalController::class, 'registerForm'])->name('tascas-proposals.create');
    Route::post('/register/tascas-proposals', [TascaProposalController::class, 'store'])->name('tascas-proposals.store');
    Route::get('/tascas-proposals', [TascaProposalController::class, 'index'])->name('tascas-proposals.index')->middleware('auth');
    Route::get('/tascas-proposals/{tascaProposal}', [TascaProposalController::class, 'show'])->name('tascas-proposals.show')->middleware('auth');
    Route::put('/tascas-proposals/{tascaProposal}', [TascaProposalController::class, 'update'])->name('tascas-proposals.update')->middleware('auth');
    Route::post('/tascas-proposals/{tascaProposal}/approve', [TascaProposalController::class, 'approve'])->name('tascas-proposals.approve')->middleware('auth');
    Route::post('/tascas-proposals/{tascaProposal}/clone', [TascaProposalController::class, 'clone'])->name('tascas-proposals.clone')->middleware('auth');
});
