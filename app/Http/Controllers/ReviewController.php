<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Review;
use App\Models\Tasca;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReviewController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of Reviews of the specified user.
     *
     * @param int $userId
     * @return \Inertia\Response
     */
    public function index(int $userId)
    {
        $user = User::findOrFail($userId);

        $reviews = $user->customer->reviews()->get();

        return inertia('Reviews/ReviewIndex', [
            'reviews' => $reviews->load('tasca'),
            'user' => $user
        ]);
    }

    /**
     * Show the form for creating a new Review.
     *
     * @param Tasca $tasca
     * @return \Inertia\Response
     */
    public function create(Tasca $tasca)
    {
        return inertia('Reviews/ReviewForm', [
            'tasca' => $tasca
        ]);
    }

    /**
     * Store a newly created Review in storage.
     *
     * @param ReviewRequest $request
     * @param Tasca $tasca
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ReviewRequest $request, Tasca $tasca)
    {
        $validated = [
            ...$request->validated(),
            'tasca_id' => $tasca->id,
            'customer_id' => auth()->user()->customer->id,
            'created_at' => now(),
        ];

        $review = new Review($validated);

        $this->authorize('create', $review);

        $review->save();

        return redirect()->route('tascas.show', ['tasca' => $validated['tasca_id']])->with('success',
            'Publicada review con éxito. ¡Gracias por tu opinión!');
    }

    /**
     * Show the form for editing the specified Review.
     *
     * @param Tasca $tasca
     * @param int $reviewId
     * @return \Inertia\Response
     */
    public function edit(Tasca $tasca, int $reviewId)
    {
        $review = Review::findOrFail($reviewId);

        return inertia('Reviews/ReviewForm', [
            'review' => $review,
            'tasca' => $tasca
        ]);
    }

    /**
     * Update the specified Review in storage.
     *
     * @param ReviewRequest $request
     * @param Tasca $tasca
     * @param int $reviewId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ReviewRequest $request, Tasca $tasca, int $reviewId)
    {
        $review = Review::findOrFail($reviewId);

        $this->authorize('update', $review);

        $validated = [
            ...$request->validated(),
            'updated_at' => now(),
        ];

        $review->update($validated);

        return redirect()->route('tascas.show', ['tasca' => $tasca->id])->with('success',
            'Actualizada review con éxito. ¡Gracias por tu opinión!');
    }

    public function destroy(Tasca $tasca, $reviewId)
    {
        $review = Review::findOrFail($reviewId);

        $this->authorize('delete', $review);

        $review->delete();

        return redirect()->route('tascas.show', ['tasca' => $tasca->id])->with('success',
            'Eliminada review con éxito.');
    }
}
