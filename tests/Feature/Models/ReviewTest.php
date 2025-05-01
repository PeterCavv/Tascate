<?php

use App\Models\Review;
use App\Models\Customer;
use App\Models\Tasca;

it('belongs to a customer', function () {
    $customer = Customer::factory()->create();
    $review = Review::factory()->create(['customer_id' => $customer->id]);

    expect($review->customer)->toBeInstanceOf(Customer::class);
    expect($review->customer->id)->toBe($customer->id);
});

it('belongs to a tasca', function () {
    $tasca = Tasca::factory()->create();
    $review = Review::factory()->create(['tasca_id' => $tasca->id]);

    expect($review->tasca)->toBeInstanceOf(Tasca::class);
    expect($review->tasca->id)->toBe($tasca->id);
});
