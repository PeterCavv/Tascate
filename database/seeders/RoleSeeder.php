<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if ($this->isDataAlreadyGiven()) {
            return;
        }

        // Create roles
        $admin = Role::create(['name' => 'Admin']);
        $owner = Role::create(['name' => 'Owner']);
        $manager = Role::create(['name' => 'Manager']);
        $employee = Role::create(['name' => 'Employee']);
        $tasca = Role::create(['name' => 'Tasca']);

        // Admin gets all permissions
        $admin->givePermissionTo(Permission::all());

        // Owner permissions
        $owner->givePermissionTo([
            'delete tasca',
            'edit tasca settings',
            'delete tasca settings',
            'view tasca statistics',
            'view tasca menu',
            'edit tasca menu',
            'delete tasca menu',
            'view tasca hours',
            'edit tasca hours',
            'delete tasca hours',
            'edit employees',
            'delete employees',
            'view employees',
            'assign employees',
            'edit reservations',
            'delete reservations',
            'view reservations',
            'edit reviews',
            'delete reviews',
            'view reviews',
            'edit posts',
            'delete posts',
            'create posts',
            'view posts',
        ]);

        // Manager permissions
        $manager->givePermissionTo([
            'view tasca statistics',
            'view tasca menu',
            'view tasca hours',
            'view employees',
            'edit reservations',
            'delete reservations',
            'view reservations',
            'edit reviews',
            'delete reviews',
            'view reviews',
            'create posts',
            'delete posts',
            'view posts',
        ]);

        // Employee permissions
        $employee->givePermissionTo([
            'view tasca menu',
            'view tasca hours',
            'view reservations',
            'create reservations',
            'view reviews',
            'create reviews',
            'view posts',
        ]);

        // Tasca permissions
        $tasca->givePermissionTo([
            'delete tasca',
            'edit tasca settings',
            'delete tasca settings',
            'view tasca statistics',
            'view tasca menu',
            'edit tasca menu',
            'delete tasca menu',
            'view tasca hours',
            'edit tasca hours',
            'delete tasca hours',
            'edit employees',
            'delete employees',
            'view employees',
            'assign employees',
            'edit reservations',
            'delete reservations',
            'view reservations',
            'edit reviews',
            'delete reviews',
            'view reviews',
            'edit posts',
            'delete posts',
            'create posts',
            'view posts',
        ]);
    }

    private function isDataAlreadyGiven(): bool
    {
        // Check if all required roles exist
        $requiredRoles = ['Admin', 'Owner', 'Manager', 'Employee', 'Tasca'];
        $existingRoles = Role::whereIn('name', $requiredRoles)->pluck('name')->toArray();
        
        if (count($existingRoles) !== count($requiredRoles)) {
            return false;
        }

        // Check if Admin role has all permissions (as it should have all)
        $adminRole = Role::where('name', 'Admin')->first();
        if (!$adminRole || $adminRole->permissions->count() !== Permission::count()) {
            return false;
        }

        return true;
    }
}








