<?php

namespace App\Observers;

use App\Models\Reservation;

class ReservationObserver
{
    /**
     * Handle the Reservation "creating" event. If the reservation
     * does not belong to the customer or the user is not an admin,
     * throw an exception.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return void
     */
    public function deleting(Reservation $reservation)
    {
        //if(auth()->user()->id !== $reservation->user_id && auth()->user()->role !== 'admin'){
        //    throw ValidationException::withMessages([
        //        'name' => 'You cannot delete this Reservation'
        // ]);

    }
}
