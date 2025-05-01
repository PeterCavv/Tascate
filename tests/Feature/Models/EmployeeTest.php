<?php

use App\Models\Employee;
use App\Models\User;
use App\Models\Manager;

it('belongs to a user', function () {
    $user = User::factory()->create();
    $employee = Employee::factory()->create(['user_id' => $user->id]);

    expect($employee->user)->toBeInstanceOf(User::class);
    expect($employee->user->id)->toBe($user->id);
});

it('belongs to a manager', function () {
    $manager = Manager::factory()->create();
    $employee = Employee::factory()->create(['manager_id' => $manager->id]);

    expect($employee->manager)->toBeInstanceOf(Manager::class);
    expect($employee->manager->id)->toBe($manager->id);
});
