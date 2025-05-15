<?php


use App\Enums\Role;
use App\Models\Customer;
use App\Models\Tasca;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create(['role' => Role::CUSTOMER->value]);
    $this->customer = Customer::factory()->create(['user_id' => $this->user->id]);
    $this->tasca = Tasca::factory()->create();

    $this->user->refresh();
    $this->user->load('customer');
});

it('allows a customer to add a tasca to favorites if it is not already added', function () {
    expect($this->user->can('addFavorite', $this->tasca))->toBeTrue();
});

it('denies a customer to add a tasca to favorites if it is already added', function () {
    $this->customer->favoriteTascas()->attach($this->tasca->id);

    expect($this->user->can('addFavorite', $this->tasca))->toBeFalse();
});

it('denies a not customer to add a tasca to favorites', function () {
    $user = User::factory()->create(['role' => Role::EMPLOYEE->value]);

    expect($user->can('addFavorite', $this->tasca))->toBeFalse();
});
