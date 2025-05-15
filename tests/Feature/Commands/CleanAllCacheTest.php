<?php

use App\Console\Commands\CleanAllCache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

it('cleans all cache', function () {
    // Arrange
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    // Act
    $this->artisan(CleanAllCache::class);
    // Assert
    $this->artisan('view:clear')->assertExitCode(0);
    $this->artisan('cache:clear')->assertExitCode(0);
    $this->artisan('route:clear')->assertExitCode(0);
    $this->artisan('config:clear')->assertExitCode(0);
});
