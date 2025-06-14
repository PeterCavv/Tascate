<?php

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use App\Enums\Role as UseRole;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Ensure roles exist
    $this->seed([
        PermissionSeeder::class,
        RoleSeeder::class,
    ]);
});

it('creates a new admin user successfully', function () {
    // Arrange
    $name = 'Admin User';
    $email = 'admin@tascate.com';
    $password = 'admin123';

    // Act
    $this->artisan('admin:create')
        ->expectsQuestion('What is the admin name?', $name)
        ->expectsQuestion('What is the admin email?', $email)
        ->expectsQuestion('What is the admin password?', $password)
        ->assertExitCode(0);

    // Assert
    $user = User::where('email', $email)->first();
    expect($user)->not->toBeNull()
        ->and($user->name)->toBe($name)
        ->and($user->email)->toBe($email)
        ->and(Hash::check($password, $user->password))->toBeTrue()
        ->and($user->hasRole(UseRole::ADMIN->value))->toBeTrue();
});

it('creates admin user with command options', function () {
    // Arrange
    $name = 'Admin User';
    $email = 'admin@tascate.com';
    $password = 'admin123';

    // Act
    $this->artisan('admin:create', [
        '--name' => $name,
        '--email' => $email,
        '--password' => $password,
    ])->assertExitCode(0);

    // Assert
    $user = User::where('email', $email)->first();
    expect($user)->not->toBeNull()
        ->and($user->name)->toBe($name)
        ->and($user->email)->toBe($email)
        ->and(Hash::check($password, $user->password))->toBeTrue()
        ->and($user->hasRole(UseRole::ADMIN->value))->toBeTrue();
});

it('validates required fields', function () {
    // Act & Assert
    $this->artisan('admin:create')
        ->expectsQuestion('What is the admin name?', '')
        ->expectsQuestion('What is the admin email?', '')
        ->expectsQuestion('What is the admin password?', '')
        ->assertExitCode(1)
        ->expectsOutput(__('validation.filled', ['attribute' => __('validation.attributes.name')]))
        ->expectsOutput(__('validation.filled', ['attribute' => __('validation.attributes.email')]))
        ->expectsOutput(__('validation.filled', ['attribute' => __('validation.attributes.password')]));
});

it('validates email format', function () {
    // Act & Assert
    $this->artisan('admin:create')
        ->expectsQuestion('What is the admin name?', 'Admin User')
        ->expectsQuestion('What is the admin email?', 'invalid-email')
        ->expectsQuestion('What is the admin password?', 'password123')
        ->assertExitCode(1)
        ->expectsOutput(__('validation.email', ['attribute' => __('validation.attributes.email')]));
});

it('validates password length', function () {
    // Act & Assert
    $this->artisan('admin:create')
        ->expectsQuestion('What is the admin name?', 'Admin User')
        ->expectsQuestion('What is the admin email?', 'admin@tascate.com')
        ->expectsQuestion('What is the admin password?', 'short')
        ->assertExitCode(1)
        ->expectsOutput(__('validation.min.string', [
            'attribute' => __('validation.attributes.password'),
            'min' => 8,
        ]));
});

it('prevents duplicate admin creation without confirmation', function () {
    // Arrange
    $admin = User::factory()->create();
    $admin->assignRole(UseRole::ADMIN->value);

    // Act & Assert
    $this->artisan('admin:create')
        ->expectsQuestion('What is the admin name?', 'New Admin')
        ->expectsQuestion('What is the admin email?', 'newadmin@tascate.com')
        ->expectsQuestion('What is the admin password?', 'password123')
        ->expectsConfirmation('Do you wish to continue?', 'no')
        ->assertExitCode(0);

    // Assert no new admin was created
    expect(User::where('email', 'newadmin@tascate.com')->exists())->toBeFalse();
});
