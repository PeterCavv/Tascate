<?php

namespace Database\Factories;

use App\Models\Tasca;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TascaFactory extends Factory
{
    protected $model = Tasca::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create()->id,
            'name' => $this->faker->sentence(3),
            'address' => $this->faker->address(),
            'menu' => $this->faker->sentence(5),
            'opening_time' => $this->faker->time(),
            'closing_time' => $this->faker->time(),
            'capacity' => $this->faker->numberBetween(1, 100),
        ];
    }
}
