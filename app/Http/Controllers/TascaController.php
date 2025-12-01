<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Http\Requests\Tasca\UpdateTascaRequest;
use App\Models\Reservation;
use App\Models\Tasca;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TascaController extends Controller
{
    use AuthorizesRequests;

    public function index(#[CurrentUser] ?User $user = null): Response
    {
        $filters = collect([
            'search' => request()->query('search'),
            'only_favorites' => request('only_favorites'),
        ]);

        $query = Tasca::with([
                'reviews:id,rating,tasca_id',
            ])
            ->latest()
            ->filter($filters, $user);

        $pagination = $query->paginate(10)
            ->withQueryString()
            ->through(fn ($tasca) => [
                'id' => $tasca->id,
                'name' => $tasca->name,
                'address' => $tasca->address,
                'picture' => $tasca->picture,
                'average_rating' => $tasca->averageRating,
                'is_favorite' => $tasca->isFavorite($user),
        ]);

        return inertia('Tascas/TascasIndex', [
            'filters' => $filters,
            'pagination' => $pagination,
        ]);
    }

    public function show(#[CurrentUser] ?User $user, Tasca $tasca): Response
    {
        if ($tasca->picture) {
            $tasca->picture = asset($tasca->picture);
        }
        $tasca_picture_path = $tasca->picture;
        if (auth()->check()) {
            if (auth()->user()->hasRole(Role::CUSTOMER->value)) {
                $tasca->is_favorite = auth()->user() ?
                auth()->user()->customer->favoriteTascas->contains($tasca->id) : false;
            } else {
                $tasca->is_favorite = false;
            }
        }

        return inertia('Tascas/TascaShow', [
            'tasca' => $tasca->load('user', 'reservations', 'reviews.customer.user'),
            'tasca_picture_path' => $tasca_picture_path,
            'user_review' => $user ?
                $user->customer?->reviews?->where('tasca_id', $tasca->id) : null,
        ]);
    }

    public function edit(Tasca $tasca): Response
    {
        return inertia('Tascas/TascaEdit', [
            'tasca' => $tasca,
        ]);
    }

    public function update(UpdateTascaRequest $request, Tasca $tasca): RedirectResponse
    {
        $this->authorize('update', $tasca);

        $validated = $request->validated();

        if ($request->hasFile('picture')) {
            $validated['picture'] = $request->file('picture')->store('tascas', 'public');
        } else {
            unset($validated['picture']);
        }

        $tasca->update($validated);

        return to_route('tascas.show', $tasca)
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
            ->map(function ($tasca) use ($user) {
                $tasca->is_favorite = true;
                return $tasca;
            });

        return Inertia::render('Tascas/TascasIndex', [
            'tascas' => $favoriteTascas,
        ]);
    }

    public function editTascaLocation(Tasca $tasca)
    {

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

        return redirect()->route('tascas.show', $tasca)->with(
            'success',
            'Ubicación de la tasca actualizada correctamente.'
        );
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
