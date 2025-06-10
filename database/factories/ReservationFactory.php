<?php

namespace Database\Factories;

use App\Enums\Role;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;
use App\Models\Tasca;
use App\Models\TascaFactory;
use App\Models\CustomerFactory;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition(): array
    {
        $tasca = Tasca::factory()->create();

        $customer =  Customer::factory()->create();

        return [
            'tasca_id' => $tasca->id,
            'customer_id' => $customer->id,
            'reservation_price' => $tasca->reservation_price,
            'reservation_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'reservation_time' => $this->faker->time(),
            'people' => $this->faker->numberBetween(1, $tasca->capacity),
            'observations' => $this->faker->sentence(),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function ($reservation) {
            $tasca = Tasca::where('id', $reservation->tasca_id)->first();
            $userTasca = $tasca->user;

            $customer = Customer::where('id', $reservation->customer_id)->first();
            $userCustomer = $customer->user;

            $userTasca->syncRoles(Role::TASCA->value);
            $userCustomer->syncRoles(Role::CUSTOMER->value);
        });
    }
}
