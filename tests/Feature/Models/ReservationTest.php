<?php

use App\Models\Tasca;
use App\Models\User;
use App\Models\Employee;
use App\Models\Manager;
use App\Models\Property;
use App\Models\Review;
use App\Models\Reservation;

it('belongs to a user', function () {
    $user = User::factory()->create();
    $tasca = Tasca::factory()->create(['user_id' => $user->id]);

    expect($tasca->user)->toBeInstanceOf(User::class);
    expect($tasca->user->id)->toBe($user->id);
});

it('has many employees', function () {
    $tasca = Tasca::factory()->create();
    $employees = Employee::factory()->count(3)->create(['tasca_id' => $tasca->id]);

    expect($tasca->employee)->toHaveCount(3);
    expect($tasca->employee->first())->toBeInstanceOf(Employee::class);
});

it('has one manager', function () {
    $tasca = Tasca::factory()->create();
    $manager = Manager::factory()->create(['tasca_id' => $tasca->id]);

    expect($tasca->manager)->toBeInstanceOf(Manager::class);
    expect($tasca->manager->id)->toBe($manager->id);
});

it('belongs to many properties', function () {
    $tasca = Tasca::factory()->create();
    $properties = Property::factory()->count(2)->create();
    $tasca->properties()->attach($properties);

    expect($tasca->properties)->toHaveCount(2);
    expect($tasca->properties->first())->toBeInstanceOf(Property::class);
})->skip('Este test se saltó porque no se ha implementado el modelo properties, Preguntar a PETER');

it('has many reviews', function () {
    $tasca = Tasca::factory()->create();
    $reviews = Review::factory()->count(4)->create(['tasca_id' => $tasca->id]);

    expect($tasca->reviews)->toHaveCount(4);
    expect($tasca->reviews->first())->toBeInstanceOf(Review::class);
});

it('has many reservations', function () {
    $tasca = Tasca::factory()->create();
    $reservations = Reservation::factory()->count(3)->create(['tasca_id' => $tasca->id]);

    expect($tasca->reservations)->toHaveCount(3);
    expect($tasca->reservations->first())->toBeInstanceOf(Reservation::class);
});
