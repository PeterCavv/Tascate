<?php


use App\Enums\Role;
use App\Models\Customer;
use App\Models\Tasca;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed([
        RoleSeeder::class,
    ]);

    $this->customer = Customer::factory()->create();

    $this->tasca = Tasca::factory()->create();

});

it('allows a customer to add a tasca to favorites if it is not already added', function () {
    expect($this->customer->user->can('addFavorite', $this->tasca))->toBeTrue();
});

it('denies a customer to add a tasca to favorites if it is already added', function () {
    $this->customer->favoriteTascas()->attach($this->tasca->id);

    expect($this->customer->user->can('addFavorite', $this->tasca))->toBeFalse();
});

it('denies a not customer to add a tasca to favorites', function () {
    $user = User::factory()->create();
    $user->assignRole(Role::EMPLOYEE->value);

    expect($user->can('addFavorite', $this->tasca))->toBeFalse();
});
