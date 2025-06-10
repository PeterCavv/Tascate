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
            'user_id' => User::factory([
                'name' => $this->faker->unique()->name(),
                'email' => $this->faker->unique()->safeEmail(),
                'password' => bcrypt('12345678'),
            ])->create()->id,
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function ($customer) {
            $user = User::where('id', $customer->user_id)->first();
            $user->syncRoles(Role::CUSTOMER->value);
        });
    }
}
