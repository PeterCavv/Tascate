<?php

use App\Models\Owner;
use App\Models\User;
use App\Models\Property;


it('belongs to a user', function () {
    $user = User::factory()->create();
    $owner = Owner::factory()->create(['user_id' => $user->id]);

    expect($owner->user)->toBeInstanceOf(User::class);
    expect($owner->user->id)->toBe($user->id);
});

it('has many properties through the pivot table', function () {
    $owner = Owner::factory()->create();
    $property = Property::factory()->create();
    $owner->properties()->attach($property->id);

    expect($owner->properties->first())->toBeInstanceOf(Property::class);
    expect($owner->properties->first()->id)->toBe($property->id);
})->skip('Este test se saltó porque no se ha implementado el modelo properties, Preguntar a PETER');
