<?php

use App\Models\Manager;
use App\Models\Tasca;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('employee form page can be rendered', function () {
    $user = User::factory()->create();
    $tascas = Tasca::factory()->count(3)->create();
    $managers = Manager::factory()->count(2)->create();

    $response = $this->actingAs($user)
        ->get(route('employees.create'));

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('employees/EmployeeForm')
        ->has('tascas', 3)
        ->has('managers', 2)
        ->has('auth')
    );
})->todo();

test('employee can be created with required fields', function () {
    $user = User::factory()->create();
    $tasca = Tasca::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('employees.store'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'tasca_id' => $tasca->id,
        ]);

    $response->assertRedirect(route('employees.index'));
    $this->assertDatabaseHas('employees', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'tasca_id' => $tasca->id,
    ]);
})->todo();

test('employee can be created with manager', function () {
    $user = User::factory()->create();
    $tasca = Tasca::factory()->create();
    $manager = Manager::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('employees.store'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'tasca_id' => $tasca->id,
            'manager_id' => $manager->id,
        ]);

    $response->assertRedirect(route('employees.index'));
    $this->assertDatabaseHas('employees', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'tasca_id' => $tasca->id,
        'manager_id' => $manager->id,
    ]);
})->todo();

test('validation fails with invalid data', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('employees.store'), [
            'name' => '',
            'email' => 'invalid-email',
            'tasca_id' => 999,
        ]);

    $response->assertSessionHasErrors(['name', 'email', 'tasca_id']);
});

test('tasca field is auto-filled for user with tasca', function () {
    $tasca = Tasca::factory()->create();
    $user = User::factory()->create(['tasca_id' => $tasca->id]);

    $response = $this->actingAs($user)
        ->get(route('employees.create'));

    $response->assertInertia(fn (Assert $page) => $page
        ->component('Employees/EmployeeForm')
        ->where('auth.user.tasca_id', $tasca->id)
    );
})->todo();

test('manager field is auto-filled for user with manager', function () {
    $manager = Manager::factory()->create();
    $user = User::factory()->create(['manager_id' => $manager->id]);

    $response = $this->actingAs($user)
        ->get(route('employees.create'));

    $response->assertInertia(fn (Assert $page) => $page
        ->component('Employees/EmployeeForm')
        ->where('auth.user.manager_id', $manager->id)
    );
})->todo();

test('non authenticated user cannot access employee form', function () {
    $response = $this->get(route('employees.create'));
    $response->assertRedirect(route('login'));
});
