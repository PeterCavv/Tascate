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
            'tasca_id' => Tasca::factory()->create()->id,
            'customer_id' => Customer::factory()->create()->id,
            'body' => $this->faker->text(200),
            'rating' => $this->faker->numberBetween(1, 5)
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
