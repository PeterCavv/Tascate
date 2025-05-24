<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tasca\UpdateTascaRequest;
use App\Models\Tasca;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;

class TascaController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $tascas = Tasca::with('user', 'reservations', 'reviews.customer.user')->get();

        return Inertia::render('Tascas/TascasIndex', [
            'tascas' => $tascas,
        ]);
    }

    public function show(Tasca $tasca)
    {
        if ($tasca->picture) {
            $tasca->picture = asset($tasca->picture);
        }
        $tasca_picture_path = $tasca->picture;

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

        return redirect()->route('tascas.show', $tasca)->with('success',
            'Tasca actualizada correctamente.');
    }
}
