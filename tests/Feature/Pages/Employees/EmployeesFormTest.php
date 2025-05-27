<?php

use App\Models\Manager;
use App\Models\Tasca;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;

//uses(RefreshDatabase::class);

it('allows to create an employee', function () {
    $user = User::factory()->create();
    $tasca = Tasca::factory()->create();
    $manager = User::factory()->create();

    $this->actingAs($user)
        ->post(route('employees.store'), [
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'tasca_id' => $tasca->id,
            'manager_id' => $manager->id,
        ])
        ->assertRedirect(); // o ->assertStatus(302)

    expect(User::where('email', 'juan@example.com')->exists())->toBeTrue();
})->todo();

it('requires the name field', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('employees.store'), [
            'email' => 'juan@example.com',
            'tasca_id' => 1,
            'manager_id' => 1,
        ])
        ->assertSessionHasErrors(['name']);
})->todo();

it('requires the email field', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('employees.store'), [
            'name' => 'Juan Pérez',
            'email' => 'correo-no-valido',
            'tasca_id' => 1,
            'manager_id' => 1,
        ])
        ->assertSessionHasErrors(['email']);
})->todo();

it('asigns automatically a tasca if the user has already one', function () {
    $tasca = Tasca::factory()->create();
    $user = User::factory()->create([
        'tasca_id' => $tasca->id,
    ]);

    $this->actingAs($user)
        ->post(route('employees.store'), [
            'name' => 'Empleado Auto Tasca',
            'email' => 'auto@tasca.com',
            'manager_id' => null,
        ])
        ->assertRedirect();

    $employee = Employee::where('email', 'auto@tasca.com')->first();
    expect($employee->tasca_id)->toBe($tasca->id);
})->todo();

it('asigns automatically a manager if the tasca has already one', function () {
    $manager = User::factory()->create();
    $user = User::factory()->create([
        'manager_id' => $manager->id,
    ]);

    $this->actingAs($user)
        ->post(route('employees.store'), [
            'name' => 'Empleado Auto Manager',
            'email' => 'auto@manager.com',
            'tasca_id' => null,
        ])
        ->assertRedirect();

    $employee = Employee::where('email', 'auto@manager.com')->first();
    expect($employee->manager_id)->toBe($manager->id);
})->todo();

it('non authenticated user cannot  create employees', function () {
    $this->post(route('employees.store'), [
        'name' => 'Invitado',
        'email' => 'no@auth.com',
        'tasca_id' => 1,
        'manager_id' => 1,
    ])->assertRedirect(route('login'));
});


test('non authenticated user cannot access employee form', function () {
    $response = $this->get(route('employees.create'));
    $response->assertRedirect(route('login'));
});
