<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if ($this->isDataAlreadyGiven()) {
            return;
        }

        User::factory()->create([
            'name' => 'Admin Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('12345678'),
            'role' => Role::ADMIN->value,
        ])->assignRole(Role::ADMIN->value);

        User::factory()->count(10)->create([
            'role' => Role::ADMIN->value,
        ])->each(function ($user) {
            $user->assignRole(Role::ADMIN->value);
        });

        User::factory()->count(10)->create([
            'role' => Role::TASCA->value,
        ])->each(function ($user) {
            $user->assignRole(Role::TASCA->value);
        });

        User::factory()->count(10)->create([
            'role' => Role::MANAGER->value,
        ])->each(function ($user) {
            $user->assignRole(Role::MANAGER->value);
        });

        User::factory()->count(10)->create([
            'role' => Role::EMPLOYEE->value,
        ])->each(function ($user) {
            $user->assignRole(Role::EMPLOYEE->value);
        });

        User::factory()->count(10)->create([
            'role' => Role::EMPLOYEE->value
        ])->each(function ($user) {
            $user->assignRole(Role::CUSTOMER->value);
        });


    }

    private function isDataAlreadyGiven(): bool
    {
        return User::where('email', 'test@example.com')->exists();
    }
}
