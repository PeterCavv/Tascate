<?php

use App\Models\Employee;
use App\Models\Manager;
use App\Models\Tasca;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('employees index page can be rendered', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('employees.index'));

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('employees/Employees')
        ->has('employees')
        ->has('can')
    );
})->todo();

test('employees are displayed in the index page', function () {
    $user = User::factory()->create();
    $employees = Employee::factory()->count(3)->create();

    $response = $this->actingAs($user)
        ->get(route('employees.index'));

    $response->assertInertia(fn (Assert $page) => $page
        ->component('employees/Employees')
        ->has('employees', 3)
        ->where('employees.0.id', $employees[0]->id)
        ->where('employees.0.name', $employees[0]->name)
        ->where('employees.0.email', $employees[0]->email)
    );
})->todo();

test('employee can be deleted', function () {
    $user = User::factory()->create();
    $employee = Employee::factory()->create();

    $response = $this->actingAs($user)
        ->delete(route('employees.destroy', $employee));

    $response->assertRedirect(route('employees.index'));
    $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
})->todo();

test('non authenticated user cannot access employees index', function () {
    $response = $this->get(route('employees.index'));
    $response->assertRedirect(route('login'));
});

test('employee card shows correct information', function () {
    $user = User::factory()->create();
    $tasca = Tasca::factory()->create();
    $manager = Manager::factory()->create();
    $employee = Employee::factory()->create([
        'tasca_id' => $tasca->id,
        'manager_id' => $manager->id,
    ]);

    $response = $this->actingAs($user)
        ->get(route('employees.index'));

    $response->assertInertia(fn (Assert $page) => $page
        ->component('employees/Employees')
        ->has('employees', 1)
        ->where('employees.0.id', $employee->id)
        ->where('employees.0.name', $employee->name)
        ->where('employees.0.email', $employee->email)
        ->where('employees.0.tasca_id', $tasca->id)
        ->where('employees.0.manager_id', $manager->id)
    );
})->todo();
