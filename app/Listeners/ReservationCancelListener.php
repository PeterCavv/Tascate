<?php

namespace App\Listeners;

use App\Events\ReservationCancelEvent;
use App\Mail\ReservationCancelledMail;
use App\Models\Reservation;
use Illuminate\Support\Facades\Mail;

class ReservationCancelListener
{
    public function handle(ReservationCancelEvent $event): void
    {
        Mail::to($event->email)->queue(
            new ReservationCancelledMail(
                $event->tasca,
                $event->customer,
                $event->reservation
            )
        );

        Reservation::find($event->reservation['id'])?->delete();
    }
}
