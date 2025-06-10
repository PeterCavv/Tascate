<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

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
        Role::create(['name' => \App\Enums\Role::ADMIN->value]);
        Role::create(['name' => \App\Enums\Role::TASCA->value]);
        Role::create(['name' => \App\Enums\Role::MANAGER->value]);
        Role::create(['name' => \App\Enums\Role::EMPLOYEE->value]);
        Role::create(['name' => \App\Enums\Role::CUSTOMER->value]);

    }

    private function isDataAlreadyGiven(): bool
    {
        return Role::where('name', \App\Enums\Role::ADMIN->value)->exists()
            && Role::where('name', \App\Enums\Role::MANAGER->value)->exists()
            && Role::where('name', \App\Enums\Role::EMPLOYEE->value)->exists()
            && Role::where('name', \App\Enums\Role::TASCA->value)->exists()
            && Role::where('name', \App\Enums\Role::CUSTOMER->value)->exists();

    }
}
