<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Manager;
use App\Models\Tasca;
use App\Models\User;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create()->id,
            'tasca_id' => Tasca::factory()->create()->id,
            'manager_id' => Manager::factory()->create()->id,
        ];
    }
}
