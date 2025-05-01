<?php

use App\Models\Customer;
use App\Models\User;
use App\Models\Review;
use App\Models\Reservation;

it('belongs to a user', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id]);

    expect($customer->user)->toBeInstanceOf(User::class);
    expect($customer->user->id)->toBe($user->id);
});

it('has many reviews', function () {
    $customer = Customer::factory()->create();
    $review = Review::factory()->create(['customer_id' => $customer->id]);

    expect($customer->reviews->first())->toBeInstanceOf(Review::class);
    expect($customer->reviews->first()->id)->toBe($review->id);
});

it('has many reservations', function () {
    $customer = Customer::factory()->create();
    $reservation = Reservation::factory()->create(['customer_id' => $customer->id]);

    expect($customer->reservations->first())->toBeInstanceOf(Reservation::class);
    expect($customer->reservations->first()->id)->toBe($reservation->id);
});
