<?php


use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\RoleSeeder;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed([
        RoleSeeder::class,
    ]);

    $this->admin = User::factory()->create();
    $this->admin->assignRole(Role::ADMIN->value);

    $this->customer = User::factory()->create();
    $this->customer->assignRole(Role::CUSTOMER->value);

    $this->tasca = User::factory()->create();
    $this->tasca->assignRole(Role::TASCA->value);
});

it('allows to create a user being an admin or a tasca', function () {
    expect($this->admin->can('create', User::class))->toBeTrue()
        ->and($this->tasca->can('create', User::class))->toBeTrue();
});

it('denies to create a user not being a admin or a tasca', function () {
    $user = User::factory()->create();
    $user->assignRole(Role::CUSTOMER->value);
    expect($user->can('create', User::class))->toBeFalse()
        ->and($this->customer->can('create', User::class))->toBeFalse();
});

it('allows any non-admin to delete themself', function () {
    expect($this->customer->can('delete', $this->customer))->toBeTrue();
});

it('allow a Tasca to delete one of his Employees', function () {
    $employee = User::factory()->create();
    $employee->assignRole(Role::EMPLOYEE->value);

    expect($this->tasca->can('delete', $employee))->toBeTrue();
});

it('denies any user from deleting an Admin', function () {
    $otherTasca = User::factory()->create();
    $otherTasca->assignRole(Role::TASCA->value);

    $otherUser = User::factory()->create();
    $otherUser->assignRole(Role::CUSTOMER->value);

    expect($this->admin->can('delete', $this->admin))->toBeFalse()
        ->and($otherTasca->can('delete', $this->admin))->toBeFalse()
        ->and($otherUser->can('delete', $this->admin))->toBeFalse();
});
