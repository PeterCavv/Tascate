<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Traits\HasDataCheck;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    use HasDataCheck;

    public function run(): void
    {
        if ($this->isDataAlreadyGiven(Reservation::class)) {
            return;
        }

        Reservation::factory()->count(15)->create();
    }
}
