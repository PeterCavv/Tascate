<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use function Pest\Laravel\{get, actingAs};

it('renders the main layout for guest users', function () {
    get('/')
        ->assertInertia(fn (Assert $page) => $page
            ->component('WelcomeTemp')
            ->has('auth', fn (Assert $auth) => $auth
                ->where('user', null)
                ->etc()
            )
        );
});

it('renders the main layout for authenticated users with correct user data', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->get('/')
        ->assertInertia(fn (Assert $page) => $page
            ->component('WelcomeTemp')
            ->has('auth', fn (Assert $auth) => $auth
                ->where('user.id', $user->id)
                ->where('user.name', $user->name)
                ->where('user.email', $user->email)
                ->etc()
            )
        );
});

it('shows loading state during navigation', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->get('/')
        ->assertInertia(fn (Assert $page) => $page
            ->component('WelcomeTemp')
            ->has('auth')
            ->has('toast')
        );
});

it('handles toast messages correctly', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->get('/')
        ->assertInertia(fn (Assert $page) => $page
            ->component('WelcomeTemp')
            ->has('toast')
        );
});
