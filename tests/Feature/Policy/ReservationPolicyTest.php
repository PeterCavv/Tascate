<?php


use App\Enums\Role;
use App\Models\Customer;
use App\Models\Reservation;
use App\Models\Tasca;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->customerUser = User::factory()->create([
        'role' => Role::CUSTOMER->value,
    ]);

    $this->tascaUser = User::factory()->create([
        'role' => Role::TASCA->value,
    ]);

    $this->customer = Customer::factory()->create([
        'user_id' => $this->customerUser->id,
    ]);
    $this->tasca = Tasca::factory()->create([
        'user_id' => $this->tascaUser->id,
    ]);

    $this->reservation = Reservation::factory()->create([
        'customer_id' => $this->customer->id,
        'tasca_id'    => $this->tasca->id,
    ]);
});

it('allows a Customer to create a Reservation', function () {
    expect($this->customerUser->can('create', Reservation::class))->toBeTrue();
});

it('denies a non-Customer user to create a Reservation', function () {
    expect($this->tascaUser->can('create', Reservation::class))->toBeFalse();
});

it('allows a Customer to delete a Reservation', function () {
    expect($this->customerUser->can('delete', $this->reservation))->toBeTrue();
});

it('denies a non-Customer user to delete a Reservation', function () {
    expect($this->tascaUser->can('delete', $this->reservation))->toBeFalse();
});

it('denies a Customer to delete a Reservation that is not his', function () {
    $otherCustomer = User::factory()->create([
        'role' => Role::CUSTOMER->value,
    ]);

    expect($otherCustomer->can('delete', $this->reservation))->toBeFalse();
});

it('allows a Customer to update his Reservation', function () {
    expect($this->customerUser->can('update', $this->reservation))->toBeTrue();
});

it('denies a non-Customer user to update a Reservation', function () {
    expect($this->tascaUser->can('update', $this->reservation))->toBeFalse();
});

it('denies a Customer to update a Reservation that is not his', function () {
    $otherCustomer = User::factory()->create([
        'role' => Role::CUSTOMER->value,
    ]);

    expect($otherCustomer->can('update', $this->reservation))->toBeFalse();
});
