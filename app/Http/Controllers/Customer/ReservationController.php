<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reservation\StoreReservationRequest;
use App\Http\Requests\Reservation\UpdateReservationRequest;
use App\Http\Resources\Reservation\ReservationResource;
use App\Models\Reservation;
use Illuminate\Support\Arr;

class ReservationController extends Controller
{
    /**
     * Create a new Reservation into DB.
     * @param StoreReservationRequest $request
     * @return ReservationResource
     */
    public function store(StoreReservationRequest $request)
    {
        $reservationData = $request->validated();

        return new ReservationResource(
            Reservation::create($reservationData)
        );
    }

    /**
     * Update a Reservation. Only can be updated 'reservation_price'.
     * @return ReservationResource
     */
    public function update(UpdateReservationRequest $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservationData = $request->validated();

        $updateData = Arr::only($reservationData, 'reservation_price');

        $reservation->update($updateData);

        return new ReservationResource($reservation);
    }

    /**
     * Remove a Reservation.
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();

        return response()->json(['message' => 'Reservation deleted successfully.'], 200);
    }
}
