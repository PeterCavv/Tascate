<?php

namespace App\Http\Controllers;

class WelcomePageController extends Controller
{
    public function __invoke()
    {
        return inertia('WelcomeTemp', [
            'canLogin' => auth()->check(),
            'canRegister' => false,
            'laravelVersion' => \Illuminate\Foundation\Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    }
}
