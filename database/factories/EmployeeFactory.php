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
        $tasca = Tasca::getRandomOrCreate([
            'user_id' => User::getRandomOrCreate([
                'name' => $this->faker->name(),
                'email' => $this->faker->unique()->safeEmail(),
                'password' => bcrypt('12345678'),
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
        ]);
        return [
            'user_id' => User::getRandomOrCreate([
                'name' => $this->faker->unique()->name(),
                'email' => $this->faker->unique()->safeEmail(),
                'password' => bcrypt('12345678'),
            ])->id,
            'tasca_id' => $tasca->id,
            'manager_id' => Manager::getRandomOrCreate([
                                        'tasca_id' => $tasca->id,
                                        'user_id' => User::getRandomOrCreate([
                                            'name' => $this->faker->unique()->name(),
                                            'email' => $this->faker->unique()->safeEmail(),
                                            'password' => bcrypt('12345678'),
                                        ])->id,
            ])->id,
        ];
    }
}
