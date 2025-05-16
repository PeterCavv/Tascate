<?php


use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create([
        'role' => Role::ADMIN->value
    ]);
    $this->customer = User::factory()->create([
        'role' => Role::CUSTOMER->value
    ]);
    $this->tasca = User::factory()->create([
        'role' => Role::TASCA->value
    ]);
});

it('allows to create a user being an admin or a tasca', function () {
    expect($this->admin->can('create', User::class))->toBeTrue()
        ->and($this->tasca->can('create', User::class))->toBeTrue();
});

it('denies to create a user not being a admin or a tasca', function () {
    $user = User::factory()->create(['role' => Role::CUSTOMER->value]);
    expect($user->can('create', User::class))->toBeFalse()
        ->and($this->customer->can('create', User::class))->toBeFalse();
});

it('allows any non-admin to delete themself', function () {
    expect($this->customer->can('delete', $this->customer))->toBeTrue();
});

it('allow a Tasca to delete one of his Employees', function () {
    $employee = User::factory()->create(['role' => Role::EMPLOYEE->value]);

    expect($this->tasca->can('delete', $employee))->toBeTrue();
});

it('denies any user from deleting an Admin', function () {
    $otherTasca  = User::factory()->create(['role' => Role::TASCA->value]);
    $otherUser   = User::factory()->create(['role' => Role::CUSTOMER->value]);

    expect($this->admin->can('delete', $this->admin))->toBeFalse()
        ->and($otherTasca->can('delete', $this->admin))->toBeFalse()
        ->and($otherUser->can('delete', $this->admin))->toBeFalse();
});
