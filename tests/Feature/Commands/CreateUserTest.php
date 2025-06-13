<?php

use \App\Enums\Role as UseRole;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Ensure roles exist
    $this->seed([
        PermissionSeeder::class,
        RoleSeeder::class,
    ]);

    $this->roles = array_map(fn(UseRole $r) => $r->value, UseRole::cases());
});

it('creates a new user successfully', function () {
    // Arrange
    $name = 'Test User';
    $email = 'test@tascate.com';
    $password = 'password123';
    $role = UseRole::EMPLOYEE->value;

    $roles = array_map(fn(UseRole $r) => $r->value, UseRole::cases());

    // Act
    $this->artisan('user:create')
        ->expectsQuestion('What is the user name?', $name)
        ->expectsQuestion('What is the user email?', $email)
        ->expectsQuestion('What is the user password?', $password)
        ->expectsChoice('What role should the user have?', $role, $roles)
        ->assertExitCode(0);

    // Assert
    $user = User::where('email', $email)->first();
    expect($user)->not->toBeNull()
        ->and($user->name)->toBe($name)
        ->and($user->email)->toBe($email)
        ->and(Hash::check($password, $user->password))->toBeTrue()
        ->and($user->hasRole($role))->toBeTrue();
});

it('creates user with command options', function () {
    // Arrange
    $name = 'Test User';
    $email = 'test@tascate.com';
    $password = 'password123';
    $role = UseRole::MANAGER->value;

    // Act
    $this->artisan('user:create', [
        '--name' => $name,
        '--email' => $email,
        '--password' => $password,
        '--role' => $role,
    ])->assertExitCode(0);

    // Assert
    $user = User::where('email', $email)->first();
    expect($user)->not->toBeNull()
        ->and($user->name)->toBe($name)
        ->and($user->email)->toBe($email)
        ->and(Hash::check($password, $user->password))->toBeTrue()
        ->and($user->hasRole($role))->toBeTrue();
});

it('validates required fields', function () {

    // Act & Assert
    $this->artisan('user:create')
        ->expectsQuestion('What is the user name?', '')
        ->expectsQuestion('What is the user email?', '')
        ->expectsQuestion('What is the user password?', '')
        ->expectsChoice('What role should the user have?', UseRole::EMPLOYEE->value, $this->roles)
        ->assertExitCode(1)
        ->expectsOutput(__('validation.filled', ['attribute' => __('validation.attributes.name')]))
        ->expectsOutput(__('validation.filled', ['attribute' => __('validation.attributes.email')]))
        ->expectsOutput(__('validation.filled', ['attribute' => __('validation.attributes.password')]));
});

it('validates email format', function () {
    // Act & Assert
    $this->artisan('user:create')
        ->expectsQuestion('What is the user name?', 'Test User')
        ->expectsQuestion('What is the user email?', 'invalid-email')
        ->expectsQuestion('What is the user password?', 'password123')
        ->expectsChoice('What role should the user have?', UseRole::EMPLOYEE->value, $this->roles)
        ->assertExitCode(1)
        ->expectsOutput(__('validation.email', ['attribute' => __('validation.attributes.email')]));
});

it('validates password length', function () {
    // Act & Assert
    $this->artisan('user:create')
        ->expectsQuestion('What is the user name?', 'Test User')
        ->expectsQuestion('What is the user email?', 'test@tascate.com')
        ->expectsQuestion('What is the user password?', 'short')
        ->expectsChoice('What role should the user have?', UseRole::EMPLOYEE->value, $this->roles)
        ->assertExitCode(1)
        ->expectsOutput(__('validation.min.string', [
            'attribute' => __('validation.attributes.password'),
            'min' => 8,
        ]));
});

it('validates role exists', function () {
    // Arrange
    Role::where('name', UseRole::ADMIN->value)->delete();

    // Act & Assert
    $this->artisan('user:create', [
        '--name' => 'Test User',
        '--email' => 'test@tascate.com',
        '--password' => 'password123',
        '--role' => UseRole::ADMIN->value,
    ])->assertExitCode(1)
        ->expectsOutput("Role '".UseRole::ADMIN->value."' does not exist. Please run the database seeders first.");
});

it('shows warning when creating admin user', function () {

    // Act & Assert
    $this->artisan('user:create')
        ->expectsQuestion('What is the user name?', 'Admin User')
        ->expectsQuestion('What is the user email?', 'admin@tascate.com')
        ->expectsQuestion('What is the user password?', 'password123')
        ->expectsChoice('What role should the user have?', UseRole::ADMIN->value, $this->roles)
        ->expectsConfirmation('Do you wish to continue?', 'yes')
        ->assertExitCode(0);

    // Assert
    $user = User::where('email', 'admin@tascate.com')->first();
    expect($user)->not->toBeNull()
        ->and($user->hasRole(UseRole::ADMIN->value))->toBeTrue();
});

it('prevents admin creation without confirmation', function () {
    // Act & Assert
    $this->artisan('user:create')
        ->expectsQuestion('What is the user name?', 'Admin User')
        ->expectsQuestion('What is the user email?', 'admin@tascate.com')
        ->expectsQuestion('What is the user password?', 'password123')
        ->expectsChoice('What role should the user have?', UseRole::ADMIN->value, $this->roles)
        ->expectsConfirmation('Do you wish to continue?', 'no')
        ->assertExitCode(0);

    // Assert no admin was created
    expect(User::where('email', 'admin@tascate.com')->exists())->toBeFalse();
});
