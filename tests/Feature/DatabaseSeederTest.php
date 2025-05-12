<?php

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('adds all permissions', function () {
    // Assert
    $this->assertDatabaseCount(Permission::class, 0);

    // Act
    $this->artisan('db:seed --class=PermissionSeeder');

    // Assert
    $this->assertDatabaseCount(Permission::class, 33);
    $this->assertDatabaseHas(Permission::class, ['name' => 'delete tasca']);
    $this->assertDatabaseHas(Permission::class, ['name' => 'edit tasca settings']);
    $this->assertDatabaseHas(Permission::class, ['name' => 'delete tasca settings']);
    $this->assertDatabaseHas(Permission::class, ['name' => 'view tasca statistics']);
    $this->assertDatabaseHas(Permission::class, ['name' => 'view tasca menu']);
    $this->assertDatabaseHas(Permission::class, ['name' => 'edit tasca menu']);
    $this->assertDatabaseHas(Permission::class, ['name' => 'delete tasca menu']);
    $this->assertDatabaseHas(Permission::class, ['name' => 'view tasca hours']);
    $this->assertDatabaseHas(Permission::class, ['name' => 'edit tasca hours']);
    $this->assertDatabaseHas(Permission::class, ['name' => 'delete tasca hours']);
    $this->assertDatabaseHas(Permission::class, ['name' => 'approve tasca registration']);
    $this->assertDatabaseHas(Permission::class, ['name' => 'edit employees']);
    $this->assertDatabaseHas(Permission::class, ['name' => 'delete employees']);
    $this->assertDatabaseHas(Permission::class, ['name' => 'view employees']);
    $this->assertDatabaseHas(Permission::class, ['name' => 'assign employees']);
    $this->assertDatabaseHas(Permission::class, ['name' => 'edit reservations']);
    $this->assertDatabaseHas(Permission::class, ['name' => 'delete reservations']);
    $this->assertDatabaseHas(Permission::class, ['name' => 'view reservations']);
    $this->assertDatabaseHas(Permission::class, ['name' => 'create reservations']);
    $this->assertDatabaseHas(Permission::class, ['name' => 'edit reviews']);
    $this->assertDatabaseHas(Permission::class, ['name' => 'delete reviews']);
    $this->assertDatabaseHas(Permission::class, ['name' => 'view reviews']);
    $this->assertDatabaseHas(Permission::class, ['name' => 'create reviews']);
    $this->assertDatabaseHas(Permission::class, ['name' => 'edit posts']);
    $this->assertDatabaseHas(Permission::class, ['name' => 'delete posts']);
    $this->assertDatabaseHas(Permission::class, ['name' => 'create posts']);
    $this->assertDatabaseHas(Permission::class, ['name' => 'view posts']);
    $this->assertDatabaseHas(Permission::class, ['name' => 'edit users']);
    $this->assertDatabaseHas(Permission::class, ['name' => 'delete users']);
    $this->assertDatabaseHas(Permission::class, ['name' => 'view users']);
    $this->assertDatabaseHas(Permission::class, ['name' => 'edit system']);
    $this->assertDatabaseHas(Permission::class, ['name' => 'delete system']);
    $this->assertDatabaseHas(Permission::class, ['name' => 'view system logs']);
});

it('adds permissions only once', function () {
    // Act
    $this->artisan('db:seed --class=PermissionSeeder');
    $this->artisan('db:seed --class=PermissionSeeder');

    // Assert
    $this->assertDatabaseCount(Permission::class, 33);
});

it('adds all roles', function () {
    // Assert
    $this->assertDatabaseCount(Role::class, 0);

    // Act
    $this->artisan('db:seed');

    // Assert
    $this->assertDatabaseCount(Role::class, 5);
    $this->assertDatabaseHas(Role::class, ['name' => 'Admin']);
    $this->assertDatabaseHas(Role::class, ['name' => 'Owner']);
    $this->assertDatabaseHas(Role::class, ['name' => 'Manager']);
    $this->assertDatabaseHas(Role::class, ['name' => 'Employee']);
    $this->assertDatabaseHas(Role::class, ['name' => 'Tasca']);
});

it('adds roles only once', function () {
    // Act
    $this->artisan('db:seed');
    $this->artisan('db:seed');

    // Assert
    $this->assertDatabaseCount(Role::class, 5);
});

it('creates default admin user', function () {
    // Assert
    $this->assertDatabaseCount(User::class, 0);

    // Act
    $this->artisan('db:seed');

    // Assert
    $this->assertDatabaseCount(User::class, 1);
    $this->assertDatabaseHas(User::class, [
        'name' => 'Admin Test User',
        'email' => 'test@example.com',
    ]);

    $admin = User::where('email', 'test@example.com')->first();
    expect($admin->hasRole('Admin'))->toBeTrue();
});

it('creates default admin user only once', function () {
    // Act
    $this->artisan('db:seed');
    $this->artisan('db:seed');

    // Assert
    $this->assertDatabaseCount(User::class, 1);
});
