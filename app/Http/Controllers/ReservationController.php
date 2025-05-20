<?php

namespace App\Http\Controllers;

use App\Models\Reservation;

class ReservationController extends Controller
{

    public function index()
    {
        return inertia('Reservations/ReservationIndex', [
            'reservation' => auth()->user()->load('customer.reservations'),
        ]);
    }

    public function show(Reservation $reservation)
    {
        return inertia('Reservations/ReservationShow', [
            'reservation' => $reservation->load('customer', 'tasca'),
        ]);
    }



}
