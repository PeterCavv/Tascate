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
        $tasca = Tasca::create([
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
        ]);
        $customer =  Customer::create([
            'user_id' => User::getRandomOrCreate([
                'name' => $this->faker->unique()->name(),
                'email' => $this->faker->unique()->safeEmail(),
                'password' => bcrypt('12345678'),
            ])->id,
        ]);

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
