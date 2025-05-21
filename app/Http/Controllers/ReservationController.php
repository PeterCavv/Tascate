<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reservation\StoreReservationRequest;
use App\Models\Reservation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

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



}
