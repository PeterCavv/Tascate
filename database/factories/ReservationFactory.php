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
        $tasca = Tasca::factory()->create();

        return [
            'tasca_id' => $tasca->id,
            'customer_id' => Customer::factory(),
            'reservation_price' => $tasca->reservation_price,
            'reservation_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'reservation_time' => $this->faker->time(),
            'people' => $this->faker->numberBetween(1, $tasca->capacity),
            'observations' => $this->faker->sentence(),
        ];
    }
}
