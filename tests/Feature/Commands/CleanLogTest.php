<?php

use App\Console\Commands\CleanLog;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

it('cleans the laravel.logs file', function () {
    // Arrange
    $logFile = storage_path('logs/laravel.log');
    file_put_contents($logFile, 'This is a log file');
    // Act
    $this->artisan('app:clean-log');
    // Assert
    $this->assertEmpty(file_get_contents($logFile));
});
