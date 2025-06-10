<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reservation\StoreReservationRequest;
use App\Http\Requests\Reservation\UpdateReservationRequest;
use App\Models\Reservation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use Inertia\ResponseFactory;

class ReservationController extends Controller
{

    use AuthorizesRequests;

    /**
     * Display a listing of Reservations of the authenticated customer.
     *
     * @return Response|ResponseFactory
     */
    public function index()
    {
        $reservations = auth()->user()->customer->reservations()->get();

        return inertia('Reservations/ReservationIndex', [
            'reservations' => $reservations->load('tasca', 'customer'),
        ]);
    }

    /**
     * Display the specified reservation.
     *
     * @param Reservation $reservation
     * @return Response|ResponseFactory
     */
    public function show(Reservation $reservation)
    {

        if ($reservation->tasca->picture) {
            $reservation->tasca->picture = asset($reservation->tasca->picture);
        }
        $reservation_path = $reservation->tasca->picture;

        return inertia('Reservations/ReservationShow', [
            'reservation' => $reservation->load('customer', 'tasca'),
            'reservation_path' => $reservation_path,
        ]);
    }

    /**
     * Saves a new reservation.
     *
     * @return RedirectResponse
     */
    public function store(StoreReservationRequest $request)
    {
        $this->authorize('create', Reservation::class);

        $validated = $request->validated();
        $validated['customer_id'] = auth()->user()->customer->id;

        $reservation = Reservation::create($validated);

        return redirect()->route('reservations.show', $reservation)
            ->with('toast', [
                'severity' => 'success',
                'summary' => __('messages.toast.created'),
                'detail' => __('messages.toast.reservation_created'),
            ]);
    }

    /**
     * Deletes the specified reservation.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id)
    {
        $reservation = Reservation::where('id', $id)->firstOrFail();

        $this->authorize('delete', $reservation);

        $reservation->delete();

        return redirect()->route('reservations.index',
        )->with('toast', [
            'severity' => 'success',
            'summary' => __('messages.toast.deleted'),
            'detail' => __('messages.toast.reservation_deleted'),
        ]);
    }

    /**
     * Updates the specified reservation.
     *
     * @param UpdateReservationRequest $request
     * @param Reservation $reservation
     * @return void
     */
    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        $validated = $request->validated();

        $this->authorize('update', $reservation);

        $reservation->update($validated);
    }

}
