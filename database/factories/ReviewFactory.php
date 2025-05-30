<?php

namespace Database\Factories;

use App\Enums\Role;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Tasca;
use App\Models\Customer;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'tasca_id' => Tasca::create([
                'user_id' => User::create([
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
            ])->id,
            'customer_id' => Customer::create([
                'user_id' => User::create([
                    'name' => $this->faker->unique()->name(),
                    'email' => $this->faker->unique()->safeEmail(),
                    'password' => bcrypt('12345678'),
                ])->id,
            ])->id,
            'body' => $this->faker->text(200),
            'rating' => $this->faker->numberBetween(1, 5),
        ];
    }


    public function configure(): static
    {
        return $this->afterCreating(function ($review) {
            $tasca = Tasca::where('id', $review->tasca_id)->first();
            $userTasca = $tasca->user;

            $customer = Customer::where('id', $review->customer_id)->first();
            $userCustomer = $customer->user;

            $userTasca->syncRoles(Role::TASCA->value);
            $userCustomer->syncRoles(Role::CUSTOMER->value);
        });
    }
}
