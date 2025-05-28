<?php

namespace Database\Seeders;

use App\Models\Manager;
use App\Traits\HasDataCheck;
use Illuminate\Database\Seeder;

class ManagerSeeder extends Seeder
{
    use HasDataCheck;

    public function run(): void
    {
        if ($this->isDataAlreadyGiven(Manager::class)) {
            return;
        }

        \Database\Factories\ManagerFactory::new()->count(5)->create();
    }
} 