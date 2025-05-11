<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        if ($this->isDataAlreadyGiven()) {
            return;
        }

        // Tasca Management Permissions
        Permission::create(['name' => 'delete tasca']);
        Permission::create(['name' => 'edit tasca settings']);
        Permission::create(['name' => 'delete tasca settings']);
        Permission::create(['name' => 'view tasca statistics']);
        Permission::create(['name' => 'view tasca menu']);
        Permission::create(['name' => 'edit tasca menu']);
        Permission::create(['name' => 'delete tasca menu']);
        Permission::create(['name' => 'view tasca hours']);
        Permission::create(['name' => 'edit tasca hours']);
        Permission::create(['name' => 'delete tasca hours']);

        // Employee Management Permissions
        Permission::create(['name' => 'edit employees']);
        Permission::create(['name' => 'delete employees']);
        Permission::create(['name' => 'view employees']);
        Permission::create(['name' => 'assign employees']);

        // Reservation Management Permissions
        Permission::create(['name' => 'edit reservations']);
        Permission::create(['name' => 'delete reservations']);
        Permission::create(['name' => 'view reservations']);
        Permission::create(['name' => 'create reservations']);

        // Review Management Permissions
        Permission::create(['name' => 'edit reviews']);
        Permission::create(['name' => 'delete reviews']);
        Permission::create(['name' => 'view reviews']);
        Permission::create(['name' => 'create reviews']);

        // Post Management Permissions
        Permission::create(['name' => 'edit posts']);
        Permission::create(['name' => 'delete posts']);
        Permission::create(['name' => 'create posts']);
        Permission::create(['name' => 'view posts']);

        // User Management Permissions
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);
        Permission::create(['name' => 'view users']);

        // System Management Permissions
        Permission::create(['name' => 'edit system']);
        Permission::create(['name' => 'delete system']);
        Permission::create(['name' => 'view system logs']);
    }

    private function isDataAlreadyGiven(): bool
    {
        return Permission::whereIn('name', [
            'delete tasca',
            'edit tasca settings',
            'view tasca statistics',
            'edit employees',
            'edit reservations',
            'edit reviews',
            'edit posts',
            'edit users',
            'edit system'
        ])->count() === 9;
    }
}
