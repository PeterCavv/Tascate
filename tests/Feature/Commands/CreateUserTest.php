<?php

use App\Console\Commands\CreateUser;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Ensure roles exist
    $this->seed([
        \Database\Seeders\PermissionSeeder::class,
        \Database\Seeders\RoleSeeder::class,
    ]);
});

it('creates a new user successfully', function () {
    // Arrange
    $name = 'Test User';
    $email = 'test@tascate.com';
    $password = 'password123';
    $role = 'Employee';

    // Act
    $this->artisan('user:create')
        ->expectsQuestion('What is the user name?', $name)
        ->expectsQuestion('What is the user email?', $email)
        ->expectsQuestion('What is the user password?', $password)
        ->expectsChoice('What role should the user have?', $role, ['Admin', 'Owner', 'Manager', 'Employee', 'Tasca'])
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
    $role = 'Manager';

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
        ->expectsChoice('What role should the user have?', 'Employee', ['Admin', 'Owner', 'Manager', 'Employee', 'Tasca'])
        ->assertExitCode(1)
        ->expectsOutput('The name field is required.')
        ->expectsOutput('The email field is required.')
        ->expectsOutput('The password field is required.');
});

it('validates email format', function () {
    // Act & Assert
    $this->artisan('user:create')
        ->expectsQuestion('What is the user name?', 'Test User')
        ->expectsQuestion('What is the user email?', 'invalid-email')
        ->expectsQuestion('What is the user password?', 'password123')
        ->expectsChoice('What role should the user have?', 'Employee', ['Admin', 'Owner', 'Manager', 'Employee', 'Tasca'])
        ->assertExitCode(1)
        ->expectsOutput('The email field must be a valid email address.');
});

it('validates password length', function () {
    // Act & Assert
    $this->artisan('user:create')
        ->expectsQuestion('What is the user name?', 'Test User')
        ->expectsQuestion('What is the user email?', 'test@tascate.com')
        ->expectsQuestion('What is the user password?', 'short')
        ->expectsChoice('What role should the user have?', 'Employee', ['Admin', 'Owner', 'Manager', 'Employee', 'Tasca'])
        ->assertExitCode(1)
        ->expectsOutput('The password field must be at least 8 characters.');
});

it('validates role exists', function () {
    // Arrange
    Role::where('name', 'Admin')->delete();

    // Act & Assert
    $this->artisan('user:create', [
        '--name' => 'Test User',
        '--email' => 'test@tascate.com',
        '--password' => 'password123',
        '--role' => 'Admin',
    ])->assertExitCode(1)
        ->expectsOutput("Role 'Admin' does not exist. Please run the database seeders first.");
});

it('shows warning when creating admin user', function () {
    // Act & Assert
    $this->artisan('user:create')
        ->expectsQuestion('What is the user name?', 'Admin User')
        ->expectsQuestion('What is the user email?', 'admin@tascate.com')
        ->expectsQuestion('What is the user password?', 'password123')
        ->expectsChoice('What role should the user have?', 'Admin', ['Admin', 'Owner', 'Manager', 'Employee', 'Tasca'])
        ->expectsConfirmation('Do you wish to continue?', 'yes')
        ->assertExitCode(0);

    // Assert
    $user = User::where('email', 'admin@tascate.com')->first();
    expect($user)->not->toBeNull()
        ->and($user->hasRole('Admin'))->toBeTrue();
});

it('prevents admin creation without confirmation', function () {
    // Act & Assert
    $this->artisan('user:create')
        ->expectsQuestion('What is the user name?', 'Admin User')
        ->expectsQuestion('What is the user email?', 'admin@tascate.com')
        ->expectsQuestion('What is the user password?', 'password123')
        ->expectsChoice('What role should the user have?', 'Admin', ['Admin', 'Owner', 'Manager', 'Employee', 'Tasca'])
        ->expectsConfirmation('Do you wish to continue?', 'no')
        ->assertExitCode(0);

    // Assert no admin was created
    expect(User::where('email', 'admin@tascate.com')->exists())->toBeFalse();
}); 