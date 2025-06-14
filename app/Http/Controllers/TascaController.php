<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Http\Requests\Tasca\UpdateTascaRequest;
use App\Models\Reservation;
use App\Models\Tasca;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Log;

class TascaController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        try {
            $user = auth()->user();

            $tascas = Tasca::with('user', 'reservations', 'reviews.customer.user')->get()
                ->map(function ($tasca) use ($user) {
                    $tasca->is_favorite = false;

                    if ($user && $user->hasRole(Role::CUSTOMER->value)) {
                        $tasca->is_favorite = $user->customer?->favoriteTascas->contains($tasca->id) ?? false;
                    }

                    return $tasca;
                })
                ->values();

            return Inertia::render('Tascas/TascasIndex', [
                'tascas' => $tascas,
                'userFavoriteIds' => $user && $user->customer
                    ? $user->customer->favoriteTascas->pluck('id')
                    : [],
            ]);
        } catch (Exception $e) {
            Log::error('Error in TascaController@index: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while loading tascas.');
        }
    }

    public function show(Tasca $tasca)
    {
        if ($tasca->picture) {
            $tasca->picture = asset($tasca->picture);
        }
        $tasca_picture_path = $tasca->picture;
        if (auth()->check()){
            if(auth()->user()->hasRole(Role::CUSTOMER->value)) {
            $tasca->is_favorite = auth()->user() ?
            auth()->user()->customer->favoriteTascas->contains($tasca->id) : false;
            } else {
                $tasca->is_favorite = false;
            }
        }

        return Inertia::render('Tascas/TascaShow', [
            'tasca' => $tasca->load('user', 'reservations', 'reviews.customer.user'),
            'tasca_picture_path' => $tasca_picture_path,
            'user_review' => auth()->user() ?
                auth()->user()->customer?->reviews?->where('tasca_id', $tasca->id) : null,
        ]);
    }

    public function edit(Tasca $tasca)
    {
        return Inertia::render('Tascas/TascaEdit', [
            'tasca' => $tasca,
        ]);
    }

    public function update(UpdateTascaRequest $request, Tasca $tasca)
    {
        $this->authorize('update', $tasca);

        $validated = $request->validated();

        if ($request->hasFile('picture')) {
            $validated['picture'] = $request->file('picture')->store('tascas', 'public');
        } else {
            unset($validated['picture']);
        }

        $tasca->update($validated);

        return redirect()->route('tascas.show', $tasca)
            ->with('toast', [
                'severity' => 'success',
                'summary' => __('messages.toast.updated'),
                'detail' => __('messages.toast.tasca_updated'),
            ]);
    }

    public function toggleFavorite(Tasca $tasca)
    {
        if (auth()->user()->hasRole(Role::CUSTOMER)) {

            $customer = auth()->user()->customer;

            if ($customer->favoriteTascas->contains($tasca->id)) {
                $customer->favoriteTascas()->detach($tasca->id);
            } else {
                $customer->favoriteTascas()->attach($tasca->id);
            }
        }
    }

    public function favoriteIndex()
    {

        $user = auth()->user();

        if ($user->hasRole(Role::ADMIN->value)) {
            return Inertia::render('Tascas/TascasIndex', [
                'tascas' => collect(),
            ]);
        }

        $favoriteTascas = $user->customer->favoriteTascas->load('user', 'reservations', 'reviews.customer.user')
            ->map(function ($tasca ) use ($user) {
                    $tasca->is_favorite = true;
                    return $tasca;
            });

        return Inertia::render('Tascas/TascasIndex', [
            'tascas' => $favoriteTascas,
        ]);
    }

    public function editTascaLocation(Tasca $tasca){

        $this->authorize('update', $tasca);

        return Inertia::render('Tascas/TascaLocationEdit', [
            'tasca' => $tasca,
        ]);
    }

    public function setTascaLocation(Request $request, Tasca $tasca)
    {
        $this->authorize('update', $tasca);

        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $tasca->latitude = $validated['latitude'];
        $tasca->longitude = $validated['longitude'];

        $tasca->save();

        return redirect()->route('tascas.show', $tasca)->with('success',
            'Ubicación de la tasca actualizada correctamente.');
    }
    public function gestion()
    {
        $this->authorize('update', auth()->user()->tasca);



        $reservations = Reservation::where('tasca_id', auth()->user()->tasca->id)
            ->with('customer.user')
            ->get();

        return Inertia::render('Tascas/TascaGestion', [
            'reservations' => $reservations,
        ]);
    }
}
