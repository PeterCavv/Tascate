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
            'tasca_id' => Tasca::create([
                'user_id' => User::getRandomOrCreate([
                    'name' => $this->faker->name(),
                    'email' => $this->faker->unique()->safeEmail(),
                    'password' => bcrypt('12345678'),
                    'role' => Role::TASCA->value,
                ])->id,
                'name' => $this->faker->sentence(3),
                'address' => $this->faker->address(),
                'menu' => $this->faker->sentence(5),
                'opening_time' => $this->faker->time(),
                'closing_time' => $this->faker->time(),
                'capacity' => $this->faker->numberBetween(1, 100),
                'reservation' => $this->faker->boolean(),
                'reservation_price' => $this->faker->numberBetween(0, 100),
                'telephone' => $this->faker->numerify('6########'),
                'cif' => $this->faker->unique()->numerify('#########'),
                'picture' => 'TascaPictures/Foto_Bar_Predeterminada.jpg',
            ])->id,
            'user_id' => User::create([
                'name' => $this->faker->name(),
                'email' => $this->faker->unique()->safeEmail(),
                'password' => bcrypt('12345678'),
            ])->id,
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
