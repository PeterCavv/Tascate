<?php

use \App\Http\Controllers\public\TascaController as CommonTascaController;
use Illuminate\Support\Facades\Route;

Route::prefix('public')->group(function () {
    Route::apiResource('tascas', CommonTascaController::class);
    
});
