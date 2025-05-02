<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reservation\StoreReservationRequest;
use App\Http\Resources\Reservation\ReservationResource;
use App\Models\Reservation;

class ReservationController extends Controller
{
    /**
     * Create a new Reservation.
     * @param StoreReservationRequest $request
     * @return ReservationResource
     */
    public function store(StoreReservationRequest $request)
    {
        $data = $request->validated();

        return new ReservationResource(
            Reservation::create($data)
        );
    }
}
