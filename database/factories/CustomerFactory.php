<?php

namespace Database\Factories;

use App\Enums\Role;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;


    public function definition(): array
    {
        return [
            'user_id' => User::getRandomOrCreate([
                'name' => $this->faker->unique()->name(),
                'email' => $this->faker->unique()->safeEmail(),
                'password' => bcrypt('12345678'),
                'role' => Role::CUSTOMER->value,
            ], [
                'role' => Role::CUSTOMER->value,
            ])->id,
        ];
    }
}
