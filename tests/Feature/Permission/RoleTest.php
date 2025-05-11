<?php

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

beforeEach(function () {
    $this->seed([
        \Database\Seeders\PermissionSeeder::class,
        \Database\Seeders\RoleSeeder::class,
    ]);
});

it('admin has all permissions', function () {
    $admin = User::factory()->create();
    $admin->assignRole('Admin');

    $allPermissions = Permission::all()->pluck('name')->toArray();

    foreach ($allPermissions as $permission) {
        expect($admin->hasPermissionTo($permission))->toBeTrue();
    }
});

it('owner has correct permissions', function () {
    $owner = User::factory()->create();
    $owner->assignRole('Owner');

    expect($owner->hasPermissionTo('delete tasca'))->toBeTrue()
        ->and($owner->hasPermissionTo('edit tasca settings'))->toBeTrue()
        ->and($owner->hasPermissionTo('delete tasca settings'))->toBeTrue()
        ->and($owner->hasPermissionTo('view tasca statistics'))->toBeTrue()
        ->and($owner->hasPermissionTo('view tasca menu'))->toBeTrue()
        ->and($owner->hasPermissionTo('edit tasca menu'))->toBeTrue()
        ->and($owner->hasPermissionTo('delete tasca menu'))->toBeTrue()
        ->and($owner->hasPermissionTo('view tasca hours'))->toBeTrue()
        ->and($owner->hasPermissionTo('edit tasca hours'))->toBeTrue()
        ->and($owner->hasPermissionTo('delete tasca hours'))->toBeTrue()
        ->and($owner->hasPermissionTo('edit employees'))->toBeTrue()
        ->and($owner->hasPermissionTo('delete employees'))->toBeTrue()
        ->and($owner->hasPermissionTo('view employees'))->toBeTrue()
        ->and($owner->hasPermissionTo('assign employees'))->toBeTrue()
        ->and($owner->hasPermissionTo('edit reservations'))->toBeTrue()
        ->and($owner->hasPermissionTo('delete reservations'))->toBeTrue()
        ->and($owner->hasPermissionTo('view reservations'))->toBeTrue()
        ->and($owner->hasPermissionTo('edit reviews'))->toBeTrue()
        ->and($owner->hasPermissionTo('delete reviews'))->toBeTrue()
        ->and($owner->hasPermissionTo('view reviews'))->toBeTrue()
        ->and($owner->hasPermissionTo('edit posts'))->toBeTrue()
        ->and($owner->hasPermissionTo('delete posts'))->toBeTrue()
        ->and($owner->hasPermissionTo('create posts'))->toBeTrue()
        ->and($owner->hasPermissionTo('view posts'))->toBeTrue();
});

it('manager has correct permissions', function () {
    $manager = User::factory()->create();
    $manager->assignRole('Manager');

    expect($manager->hasPermissionTo('view tasca statistics'))->toBeTrue()
        ->and($manager->hasPermissionTo('view tasca menu'))->toBeTrue()
        ->and($manager->hasPermissionTo('view tasca hours'))->toBeTrue()
        ->and($manager->hasPermissionTo('view employees'))->toBeTrue()
        ->and($manager->hasPermissionTo('view reservations'))->toBeTrue()
        ->and($manager->hasPermissionTo('view reviews'))->toBeTrue()
        ->and($manager->hasPermissionTo('view posts'))->toBeTrue()
        ->and($manager->hasPermissionTo('edit reservations'))->toBeTrue()
        ->and($manager->hasPermissionTo('edit reviews'))->toBeTrue()
        ->and($manager->hasPermissionTo('delete reservations'))->toBeTrue()
        ->and($manager->hasPermissionTo('delete reviews'))->toBeTrue()
        ->and($manager->hasPermissionTo('delete posts'))->toBeTrue()
        ->and($manager->hasPermissionTo('create posts'))->toBeTrue()
        ->and($manager->hasPermissionTo('delete tasca'))->toBeFalse()
        ->and($manager->hasPermissionTo('edit tasca settings'))->toBeFalse()
        ->and($manager->hasPermissionTo('edit employees'))->toBeFalse();
});

it('employee has correct permissions', function () {
    $employee = User::factory()->create();
    $employee->assignRole('Employee');

    expect($employee->hasPermissionTo('view tasca menu'))->toBeTrue()
        ->and($employee->hasPermissionTo('view tasca hours'))->toBeTrue()
        ->and($employee->hasPermissionTo('view reservations'))->toBeTrue()
        ->and($employee->hasPermissionTo('view reviews'))->toBeTrue()
        ->and($employee->hasPermissionTo('view posts'))->toBeTrue()
        ->and($employee->hasPermissionTo('create reservations'))->toBeTrue()
        ->and($employee->hasPermissionTo('create reviews'))->toBeTrue()
        ->and($employee->hasPermissionTo('edit tasca settings'))->toBeFalse()
        ->and($employee->hasPermissionTo('delete tasca'))->toBeFalse()
        ->and($employee->hasPermissionTo('edit employees'))->toBeFalse()
        ->and($employee->hasPermissionTo('delete posts'))->toBeFalse();

});

it('tasca has correct permissions', function () {
    $tasca = User::factory()->create();
    $tasca->assignRole('Tasca');

    expect($tasca->hasPermissionTo('delete tasca'))->toBeTrue()
        ->and($tasca->hasPermissionTo('edit tasca settings'))->toBeTrue()
        ->and($tasca->hasPermissionTo('delete tasca settings'))->toBeTrue()
        ->and($tasca->hasPermissionTo('view tasca statistics'))->toBeTrue()
        ->and($tasca->hasPermissionTo('view tasca menu'))->toBeTrue()
        ->and($tasca->hasPermissionTo('edit tasca menu'))->toBeTrue()
        ->and($tasca->hasPermissionTo('delete tasca menu'))->toBeTrue()
        ->and($tasca->hasPermissionTo('view tasca hours'))->toBeTrue()
        ->and($tasca->hasPermissionTo('edit tasca hours'))->toBeTrue()
        ->and($tasca->hasPermissionTo('delete tasca hours'))->toBeTrue()
        ->and($tasca->hasPermissionTo('edit employees'))->toBeTrue()
        ->and($tasca->hasPermissionTo('delete employees'))->toBeTrue()
        ->and($tasca->hasPermissionTo('view employees'))->toBeTrue()
        ->and($tasca->hasPermissionTo('assign employees'))->toBeTrue()
        ->and($tasca->hasPermissionTo('edit reservations'))->toBeTrue()
        ->and($tasca->hasPermissionTo('delete reservations'))->toBeTrue()
        ->and($tasca->hasPermissionTo('view reservations'))->toBeTrue()
        ->and($tasca->hasPermissionTo('edit reviews'))->toBeTrue()
        ->and($tasca->hasPermissionTo('delete reviews'))->toBeTrue()
        ->and($tasca->hasPermissionTo('view reviews'))->toBeTrue()
        ->and($tasca->hasPermissionTo('edit posts'))->toBeTrue()
        ->and($tasca->hasPermissionTo('delete posts'))->toBeTrue()
        ->and($tasca->hasPermissionTo('create posts'))->toBeTrue()
        ->and($tasca->hasPermissionTo('view posts'))->toBeTrue();
});

it('permissions are correctly inherited through roles', function () {
    $admin = User::factory()->create();
    $admin->assignRole('Admin');

    $owner = User::factory()->create();
    $owner->assignRole('Owner');

    foreach ($owner->getAllPermissions() as $permission) {
        expect($admin->hasPermissionTo($permission))->toBeTrue();
    }
});
