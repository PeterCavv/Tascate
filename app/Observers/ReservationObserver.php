<?php

namespace App\Observers;

class ReservationObserver
{
    public function deleting($reservation)
    {
        //if(auth()->user()->id !== $reservation->user_id || auth()->user()->role !== 'admin'){
        //    throw ValidationException::withMessages([
        //        'name' => 'You cannot delete this Reservation'
        // ]);
    }
}
