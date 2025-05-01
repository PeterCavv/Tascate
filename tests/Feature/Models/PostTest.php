<?php

use App\Models\Reservation;
use App\Models\Customer;
use App\Models\Tasca;

it('is fillable with the correct attributes', function () {
    $reservation = new Reservation();

    expect($reservation->getFillable())->toBe([
        'tasca_id',
        'customer_id',
        'reservation_price',
        'reservation_date',
    ]);
});

it('belongs to a customer', function () {
    $customer = Customer::factory()->create();
    $reservation = Reservation::factory()->create(['customer_id' => $customer->id]);

    expect($reservation->customer)->toBeInstanceOf(Customer::class);
    expect($reservation->customer->id)->toBe($customer->id);
});

it('belongs to a tasca', function () {
    $tasca = Tasca::factory()->create();
    $reservation = Reservation::factory()->create(['tasca_id' => $tasca->id]);

    expect($reservation->tasca)->toBeInstanceOf(Tasca::class);
    expect($reservation->tasca->id)->toBe($tasca->id);
});
