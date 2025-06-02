<?php

namespace Database\Factories;

use App\Enums\Role;
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
        $tasca = Tasca::factory()->create();
        return [
            'user_id' => User::factory()->create()->id,
            'tasca_id' => $tasca->id,
            'manager_id' => Manager::factory([
                'tasca_id' => $tasca->id])->create()->id,
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function ($employee) {
            $tasca = Tasca::where('id', $employee->tasca_id)->first();
            $userTasca = $tasca->user;

            $userTasca->syncRoles(Role::TASCA->value);
            $manager = Manager::where('id', $employee->manager_id)->first();
            $managerUser = $manager->user;

            $managerUser->syncRoles(Role::MANAGER->value);
            $employeeUser = $employee->user;
            $employeeUser->syncRoles(Role::EMPLOYEE->value);
        });
    }
}
