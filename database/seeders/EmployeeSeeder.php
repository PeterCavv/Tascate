<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Traits\HasDataCheck;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    use HasDataCheck;

    public function run(): void
    {
        if ($this->isDataAlreadyGiven(Employee::class)) {
            return;
        }

        Employee::factory()->count(10)->create();
    }
}
