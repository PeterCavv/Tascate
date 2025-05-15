<?php

namespace Database\Factories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Tasca;
use App\Models\Customer;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'tasca_id' => Tasca::factory(),
            'customer_id' => Customer::factory(),
            'body' => $this->faker->text(200),
            'rating' => $this->faker->numberBetween(1, 5),
        ];
    }
}
