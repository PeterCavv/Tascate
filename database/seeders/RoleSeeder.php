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

    }

    private function isDataAlreadyGiven(): bool
    {
        return Role::where('name', 'Admin')->exists()
            && Role::where('name', 'Owner')->exists()
            && Role::where('name', 'Manager')->exists()
            && Role::where('name', 'Employee')->exists()
            && Role::where('name', 'Tasca')->exists();

    }
}








