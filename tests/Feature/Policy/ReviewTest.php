<?php

use App\Enums\Role;
use App\Models\Tasca;
use App\Models\User;
use App\Policies\ReviewPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows a Customer to create a Review into a Tasca', function () {
    $user = User::factory()->create(['role' => Role::CUSTOMER->value]);
    $customer = $user->customer()->create(['user_id' => $user->id,]);
    $tasca = Tasca::factory()->create();

    expect($tasca->reviews->contains('customer_id', $customer->id))->toBeFalse();

    $policy = new ReviewPolicy();
    expect($policy->create($user))->toBeTrue();

    $tasca->reviews()->create([
        'customer_id' => $customer->id,
        'body' => 'Great service!',
        'rating' => 5,
    ]);

    $tasca->refresh();

    expect($tasca->reviews->contains('customer_id', $customer->id))->toBeTrue();
});

it('prevents a User to create a Review into a Tasca if it is not a Customer', function () {
    $user = User::factory()->create(['role' => Role::EMPLOYEE->value]);
    $tasca = Tasca::factory()->create();

    expect($tasca->reviews->contains('customer_id', $user->id))->toBeFalse();

    $policy = new ReviewPolicy();
    expect($policy->create($user))->toBeFalse()
        ->and($tasca->reviews->contains('customer_id', $user->id))->toBeFalse();
});

it('allows to a Customer to delete his own Review', function () {
    $user = User::factory()->create(['role' => Role::CUSTOMER->value]);
    $customer = $user->customer()->create(['user_id' => $user->id,]);
    $tasca = Tasca::factory()->create();
    $review = $tasca->reviews()->create([
        'customer_id' => $customer->id,
        'body' => 'Great service!',
        'rating' => 5,
    ]);

    expect($tasca->reviews->contains('customer_id', $customer->id))->toBeTrue();

    $policy = new ReviewPolicy();
    expect($policy->delete($user, $review))->toBeTrue();

    $review->delete();

    $tasca->refresh();

    expect($tasca->reviews->contains('customer_id', $customer->id))->toBeFalse();
});

it('allows to a Customer to update his own review', function (){
    $user = User::factory()->create(['role' => Role::CUSTOMER->value]);
    $customer = $user->customer()->create(['user_id' => $user->id,]);
    $tasca = Tasca::factory()->create();
    $review = $tasca->reviews()->create([
        'customer_id' => $customer->id,
        'body' => 'Great service!',
        'rating' => 5,
    ]);

    expect($tasca->reviews->contains('customer_id', $customer->id))->toBeTrue();

    $policy = new ReviewPolicy();
    expect($policy->update($user, $review))->toBeTrue();

    $review->update([
        'body' => 'Updated review',
        'rating' => 4,
    ]);

    $tasca->refresh();

    expect($tasca->reviews->contains('customer_id', $customer->id))->toBeTrue()
        ->and($review->body)->toBe('Updated review')
        ->and($review->rating)->toBe(4);
});
