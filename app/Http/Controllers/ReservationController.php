<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reservation\StoreReservationRequest;
use App\Http\Requests\Reservation\UpdateReservationRequest;
use App\Models\Reservation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;

class ReservationController extends Controller
{

    use AuthorizesRequests;

    public function index()
    {
        $reservations = auth()->user()->customer->reservations()->get();

        return inertia('Reservations/ReservationIndex', [
            'reservations' => $reservations->load('tasca', 'customer'),
        ]);
    }

    public function show(Reservation $reservation)
    {

        if ($reservation->tasca->picture) {
            $reservation->tasca->picture = asset('storage/' . $reservation->tasca->picture);
        }

        return inertia('Reservations/ReservationShow', [
            'reservation' => $reservation->load('customer', 'tasca'),
        ]);
    }

    public function store(StoreReservationRequest $request)
    {
        $this->authorize('create', Reservation::class);

        $validated = $request->validated();
        $validated['customer_id'] = auth()->user()->customer->id;

        Reservation::create($validated);

        return redirect()->route('tascas.index')->with('success',
            'Reserva realizada correctamente. ¡Disfruta de la experiencia!');
    }

    public function destroy($id)
    {
        $reservation = Reservation::where('id', $id)->firstOrFail();

        $this->authorize('delete', $reservation);

        $reservation->delete();

        return redirect()->route('reservations.index')->with('success',
            'Reserva cancelada correctamente. :(');
    }

    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        $validated = $request->validated();

        $this->authorize('update', $reservation);

        $reservation->update($validated);

        $this->show($reservation);
    }



}
