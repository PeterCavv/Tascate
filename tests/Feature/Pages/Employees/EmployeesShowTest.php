<?php

use App\Models\Employee;
use App\Models\Manager;
use App\Models\Tasca;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('employee show page can be rendered', function () {
    $user = User::factory()->create();
    $tasca = Tasca::factory()->create();
    $manager = Manager::factory()->create();
    $employee = Employee::factory()->create([
        'tasca_id' => $tasca->id,
        'manager_id' => $manager->id,
    ]);

    $response = $this->actingAs($user)
        ->get(route('employees.show', $employee));

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('employees/EmployeeShow')
        ->has('employee')
        ->has('can')
    );
})->todo();

test('employee details are displayed correctly', function () {
    $user = User::factory()->create();
    $tasca = Tasca::factory()->create();
    $manager = Manager::factory()->create();
    $employee = Employee::factory()->create([
        'tasca_id' => $tasca->id,
        'manager_id' => $manager->id,
    ]);

    $response = $this->actingAs($user)
        ->get(route('employees.show', $employee));

    $response->assertInertia(fn (Assert $page) => $page
        ->component('employees/EmployeeShow')
        ->where('employee.id', $employee->id)
        ->where('employee.user.name', $employee->user->name)
        ->where('employee.user.email', $employee->user->email)
        ->where('employee.tasca.name', $tasca->name)
        ->where('employee.manager.user.name', $manager->user->name)
    );
})->todo();

test('employee can be deleted from show page', function () {
    $user = User::factory()->create();
    $employee = Employee::factory()->create();

    $response = $this->actingAs($user)
        ->delete(route('employees.destroy', $employee));

    $response->assertRedirect(route('employees.index'));
    $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
})->todo();

test('edit button is shown when user has update permission', function () {
    $user = User::factory()->create();
    $employee = Employee::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('employees.show', $employee));

    $response->assertInertia(fn (Assert $page) => $page
        ->component('employees/EmployeeShow')
        ->where('can.update', true)
    );
})->todo();

test('delete button is shown when user has delete permission', function () {
    $user = User::factory()->create();
    $employee = Employee::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('employees.show', $employee));

    $response->assertInertia(fn (Assert $page) => $page
        ->component('employees/EmployeeShow')
        ->where('can.delete', true)
    );
})->todo();

test('non authenticated user cannot access employee show page', function () {
    $employee = Employee::factory()->create();

    $response = $this->get(route('employees.show', $employee));
    $response->assertRedirect(route('login'));
});
