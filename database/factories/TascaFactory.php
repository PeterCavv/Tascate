<?php

namespace Database\Factories;

use App\Enums\Role;
use App\Models\Tasca;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;


class TascaFactory extends Factory
{

    protected $model = Tasca::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory([
                                'name' => $this->faker->name(),
                                'email' => $this->faker->unique()->safeEmail(),
                                'password' => bcrypt('12345678'),
                            ])->create()->id,
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
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function ($tasca) {
            $user = User::where('id', $tasca->user_id)->first();
            $user->syncRoles(Role::TASCA->value);
        });
    }
}
