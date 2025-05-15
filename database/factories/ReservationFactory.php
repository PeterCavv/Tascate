<?php

namespace Database\Factories;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;
use App\Models\Tasca;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition(): array
    {
        return [
            'tasca_id' => Tasca::factory(),
            'customer_id' => Customer::factory(),
            'reservation_price' => $this->faker->numberBetween(0, 100),
            'reservation_date' => $this->faker->dateTimeBetween('now', '+1 month'),
        ];
    }
}
