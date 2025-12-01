<?php

namespace App\Http\Controllers;

use App\Events\ReservationCancelEvent;
use App\Http\Requests\Reservation\StoreReservationRequest;
use App\Http\Requests\Reservation\UpdateReservationRequest;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use App\Models\User;
use App\Mail\ReservationCreatedMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationTascaMail;
use Illuminate\Container\Attributes\CurrentUser;

class ReservationController extends Controller
{
    use AuthorizesRequests;

    public function index(#[CurrentUser] ?User $user): Response
    {
        $filters = collect([
            'search' => request()->query('search')
        ]);

        $query = Reservation::with('tasca:id,name,address,picture')
            ->where('customer_id', $user->customer->id)
            ->latest()
            ->filter($filters);

        $pagination = $query->paginate(10)
            ->withQueryString()
            ->through(fn ($reservation) => [
                'id' => $reservation->id,
                'tasca' => [
                    'id' => $reservation->tasca->id,
                    'name' => $reservation->tasca->name,
                    'address' => $reservation->tasca->address,
                    'picture' => $reservation->tasca->picture,
                ],
        ]);

        return inertia('Reservations/ReservationIndex', [
            'filters' => $filters,
            'pagination' => $pagination,
        ]);
    }

    public function show(Reservation $reservation): Response
    {

        $this->authorize('show', $reservation);

        if ($reservation->tasca->picture) {
            $reservation->tasca->picture = asset($reservation->tasca->picture);
        }
        $reservation_path = $reservation->tasca->picture;

        return inertia('Reservations/ReservationShow', [
            'reservation' => $reservation->load('customer', 'tasca'),
            'reservation_path' => $reservation_path,
            'reservation_date' => Carbon::parse($reservation->reservation_date)->format('Y-m-d'),

        ]);
    }

    public function store(StoreReservationRequest $request, #[CurrentUser] ?User $user): RedirectResponse
    {
        $this->authorize('create', Reservation::class);

        $validated = $request->validated();

        $reservation = new Reservation($validated);

        $reservation->customer()->associate($user->customer);
        $reservation->tasca()->associate($validated['tasca_id']);

        $reservation->save();

        Mail::to($reservation->customer->user->email)->queue(
            new ReservationCreatedMail(
                $reservation->tasca,
                $reservation->customer,
                $reservation
            )
        );

        Mail::to($reservation->tasca->user->email)->queue(
            new ReservationTascaMail(
                $reservation->customer->user,
                $reservation,
                $reservation->tasca
            )
        );

        return redirect()->route('reservations.show', $reservation)
            ->with('toast', [
                'severity' => 'success',
                'summary' => __('messages.toast.created'),
                'detail' => __('messages.toast.reservation_created'),
            ]);
    }

    public function destroy(Reservation $reservation): RedirectResponse
    {
        $this->authorize('delete', $reservation);

        event(new ReservationCancelEvent($reservation, $reservation->customer, $reservation->tasca));

        return to_route(
            'reservations.index',
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
