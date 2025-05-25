<?php

use App\Enums\Role;
use App\Models\Employee;
use App\Models\Manager;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('employee edit page can be rendered', function () {
    $user = User::factory()->create();
    $employee = Employee::factory()->create();
    $admin = User::factory()->create(['role' => Role::ADMIN]);

    $response = $this->actingAs($admin)
        ->get(route('employees.edit', $employee));

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Employees/EditEmployee')
        ->has('employee')
    );
})->todo();

test('employee can be updated', function () {
    $user = User::factory()->create();
    $employee = Employee::factory()->create();
    $newManager = Manager::factory()->create();

    $response = $this->actingAs($user)
        ->put(route('employees.update', $employee), [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'manager_id' => $newManager->id,
        ]);

    $response->assertRedirect(route('employees.show', $employee));
    $this->assertDatabaseHas('employees', [
        'id' => $employee->id,
        'manager_id' => $newManager->id,
    ]);
    $this->assertDatabaseHas('users', [
        'id' => $employee->user->id,
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
    ]);
})->todo();

test('validation fails with invalid data', function () {
    $user = User::factory()->create();
    $employee = Employee::factory()->create();

    $response = $this->actingAs($user)
        ->put(route('employees.update', $employee), [
            'name' => '',
            'email' => 'invalid-email',
            'manager_id' => 999,
        ]);

    $response->assertSessionHasErrors(['name', 'email', 'manager_id']);
})->todo();

test('form is pre-filled with employee data', function () {
    $user = User::factory()->create();
    $employee = Employee::factory()->create();
    $managers = Manager::factory()->count(3)->create();

    $response = $this->actingAs($user)
        ->get(route('employees.edit', $employee));

    $response->assertInertia(fn (Assert $page) => $page
        ->component('employees/EditEmployee')
        ->where('employee.user.name', $employee->user->name)
        ->where('employee.user.email', $employee->user->email)
        ->where('employee.manager_id', $employee->manager_id)
    );
})->todo();

test('non authenticated user cannot access employee edit page', function () {
    $employee = Employee::factory()->create();

    $response = $this->get(route('employees.edit', $employee));
    $response->assertRedirect(route('login'));
});

test('manager dropdown shows all available managers', function () {
    $user = User::factory()->create();
    $employee = Employee::factory()->create();
    $managers = Manager::factory()->count(3)->create();

    $response = $this->actingAs($user)
        ->get(route('employees.edit', $employee));

    $response->assertInertia(fn (Assert $page) => $page
        ->component('Employees/EditEmployee')
        ->has('managers', 3)
        ->where('managers.0.id', $managers[0]->id)
        ->where('managers.0.name', $managers[0]->name)
    );
})->todo();
