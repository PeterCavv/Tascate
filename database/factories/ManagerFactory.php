<?php

namespace Database\Factories;

use App\Enums\Role;
use App\Models\Manager;
use App\Models\Tasca;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class ManagerFactory extends Factory
{
    protected $model = Manager::class;

    public function definition(): array
    {
        return [
            'tasca_id' => Tasca::factory()->create()->id,
            'user_id' => User::factory([
                'name' => $this->faker->name(),
                'email' => $this->faker->unique()->safeEmail(),
                'password' => bcrypt('12345678'),
            ])->create()->id,
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function ($manager) {
            $tasca = Tasca::where('id', $manager->tasca_id)->first();
            $userTasca = $tasca->user;

            $userTasca->syncRoles(Role::TASCA->value);
            $managerUser = $manager->user;
            $managerUser->syncRoles(Role::MANAGER->value);
        });
    }
}
