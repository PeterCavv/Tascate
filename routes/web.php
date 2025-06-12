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

Route::get('/', function () {
    return app(TascaController::class)->index();
})->name('welcome');


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

Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware('auth');
Route::post('/users/{user}', [UserController::class, 'update'])->name('users.update')->middleware('auth');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('auth');

// Posts Routes


require __DIR__.'/auth.php';

// Tasca Proposals Routes


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
