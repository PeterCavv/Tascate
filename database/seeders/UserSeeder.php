<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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
        ])->assignRole('Admin');;


    }

    private function isDataAlreadyGiven(): bool
    {
        return User::where('email', 'test@example.com')->exists();

    }
}



