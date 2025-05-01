<?php

use App\Models\Manager;
use App\Models\User;
use App\Models\Employee;
use App\Models\Tasca;


it('belongs to a user', function () {
    $user = User::factory()->create();
    $manager = Manager::factory()->create(['user_id' => $user->id]);

    expect($manager->user)->toBeInstanceOf(User::class);
    expect($manager->user->id)->toBe($user->id);
});

it('has many employees', function () {
    $manager = Manager::factory()->create();
    $employee = Employee::factory()->create(['manager_id' => $manager->id]);

    expect($manager->employees->first())->toBeInstanceOf(Employee::class);
    expect($manager->employees->first()->id)->toBe($employee->id);
});

it('belongs to a tasca', function () {
    $tasca = Tasca::factory()->create();
    $manager = Manager::factory()->create(['tasca_id' => $tasca->id]);

    expect($manager->tasca)->toBeInstanceOf(Tasca::class);
    expect($manager->tasca->id)->toBe($tasca->id);
});
