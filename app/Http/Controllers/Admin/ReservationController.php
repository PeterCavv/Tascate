<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Reservation\ReservationCollection;
use App\Http\Resources\Reservation\ReservationResource;
use App\Models\Reservation;

class ReservationController extends Controller
{
    /**
     * Get all the reservations.
     * @return ReservationCollection
     */
    public function index()
    {
        return new ReservationCollection(
            Reservation::paginate(10)
        );

    }

    /**
     * Get a reservation searched by its ID.
     * @param $id Reservation's ID
     * @return ReservationResource
     */
    public function show($id)
    {
        return new ReservationResource(
            Reservation::findOrFail($id)
        );

    }
}
