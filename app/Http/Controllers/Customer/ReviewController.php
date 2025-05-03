<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Review\StoreReviewRequest;
use App\Http\Requests\Review\UpdateReviewRequest;
use App\Http\Resources\Review\ReviewResource;
use App\Models\Review;
use Illuminate\Support\Arr;

class ReviewController extends Controller
{


    /**
     * Create a new Review into DB.
     * @param StoreReviewRequest $request
     * @return ReviewResource
     */
    public function store(StoreReviewRequest $request)
    {
        $reviewData = $request->validated();

        return new ReviewResource(
            Review::create($reviewData)
        );
    }

    /**
     * Update a Review in DB.
     * @param UpdateReviewRequest $request
     * @param int $id
     * @return ReviewResource
     */
    public function update(UpdateReviewRequest $request, $id)
    {
        $review = Review::findOrFail($id);
        $reviewData = $request->validated();

        $updateData = Arr::only($reviewData, ['body', 'rating']);

        $review->update($updateData);

        return new ReviewResource($review);
    }

    /**
     * Delete a Review.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return response()->json(['message' => 'Review deleted successfully.'], 200);
    }
}
