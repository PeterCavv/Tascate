<?php

use App\Enums\Role;
use App\Models\Customer;
use App\Models\Review;
use App\Models\Tasca;
use App\Models\User;
use App\Policies\ReviewPolicy;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;



uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed([
        RoleSeeder::class,
    ]);

    $this->userCustomer = Customer::factory()->create();
    $this->userCustomer->user->assignRole(Role::CUSTOMER->value);


    $this->tasca = Tasca::factory()->create();

    $this->policy = new ReviewPolicy();

});

it('allows a Customer to create a Review into a Tasca', function () {
    $review = new Review([
        'tasca_id' => $this->tasca->id
    ]);

    expect($this->userCustomer->user->can('create', $review))->toBeTrue();
});

it('denies a Customer to create a Review in a Tasca that he already reviewed', function () {
    $this->tasca->reviews()->create([
        'customer_id' => $this->userCustomer->user_id,
        'body' => 'Great service!',
        'rating' => 5,
    ]);

    $newReview = new Review([
        'tasca_id' => $this->tasca->id
    ]);

    expect($this->userCustomer->user->can('create', $newReview))->toBeFalse();
});

it('prevents a User to create a Review into a Tasca if it is not a Customer', function () {
    $user = User::factory()->create();
    $user->assignRole(Role::EMPLOYEE->value);

    $review = new Review([
        'tasca_id' => $this->tasca->id
    ]);

    expect($this->tasca->reviews->contains('customer_id', $user->id))->toBeFalse()
        ->and($user->can('create', $review))->toBeFalse();
});

it('allows a Customer to delete his own Review', function () {
    $review = $this->tasca->reviews()->create([
        'customer_id' => $this->userCustomer->user_id,
        'body' => 'Great service!',
        'rating' => 5,
    ]);

    expect($this->userCustomer->user->can('delete', $review))->toBeTrue();
});

it('allows an Admin to delete a Review', function (){
    $admin = User::factory()->create();
    $admin->assignRole(Role::ADMIN->value);

    $review = $this->tasca->reviews()->create([
        'customer_id' => $this->userCustomer->user_id,
        'body' => 'Great service!',
        'rating' => 5,
    ]);

    expect($admin->can('delete', $review))->toBeTrue();
});

it('allows a Tasca to delete one of his reviews', function (){
    $review = $this->tasca->reviews()->create([
        'customer_id' => $this->userCustomer->user_id,
        'body' => 'Test',
        'rating' => 4,
    ]);

    expect($this->tasca->user->can('delete', $review))->toBeTrue();
});

it('denies a Customer to delete a Review not written by him', function (){
    $user = User::factory()->create();
    $user->assignRole(Role::CUSTOMER->value);

    $review = $this->tasca->reviews()->create([
        'customer_id' => $this->userCustomer->user_id,
        'body' => 'Great service!',
        'rating' => 5,
    ]);

    expect($user->can('delete', $review))->toBeFalse();
});


it('allows a Customer to update his own review', function (){
    $review = $this->tasca->reviews()->create([
        'customer_id' => $this->userCustomer->user_id,
        'body' => 'Great service!',
        'rating' => 5,
    ]);

    expect($this->userCustomer->user->can('update', $review))->toBeTrue();
});

it('denies a Customer to update a Review not written by him', function (){
    $user = User::factory()->create();
    $user->assignRole(Role::CUSTOMER->value);

    $review = $this->tasca->reviews()->create([
        'customer_id' => $this->userCustomer->user_id,
        'body' => 'Great service!',
        'rating' => 5,
    ]);

    expect($user->can('delete', $review))->toBeFalse();
});
