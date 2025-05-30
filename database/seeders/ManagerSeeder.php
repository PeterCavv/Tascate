<?php

namespace Database\Seeders;

use App\Models\Manager;
use App\Traits\HasDataCheck;
use Database\Factories\ManagerFactory;
use Illuminate\Database\Seeder;

class ManagerSeeder extends Seeder
{
    use HasDataCheck;

    public function run(): void
    {
        if ($this->isDataAlreadyGiven(Manager::class)) {
            return;
        }

        Manager::factory()->count(5)->create();
    }
}
