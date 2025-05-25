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
        ])->assignRole(Role::ADMIN->value);

    }

    private function isDataAlreadyGiven(): bool
    {
        return User::where('email', 'test@example.com')->exists();
    }
}
