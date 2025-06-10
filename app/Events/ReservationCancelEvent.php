<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use App\Models\Reservation;
use App\Models\Customer;
use App\Models\Tasca;

class ReservationCancelEvent
{
    use Dispatchable;

    public array $reservation;
    public array $customer;
    public array $tasca;
    public string $email;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation->toArray();
        $this->customer = $reservation->customer->toArray();
        $this->tasca = $reservation->tasca->toArray();
        $this->email = $reservation->customer->user->email;
    }
}
