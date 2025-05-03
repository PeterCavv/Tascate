<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Review\ReviewCollection;
use App\Http\Resources\Review\ReviewResource;
use App\Models\Review;

class ReviewController extends Controller
{
    /**
     * Get all the existing reviews.
     *
     * @return ReviewCollection
     */
    public function index()
    {
        return new ReviewCollection(
            Review::withTrashed()->paginate(10)
        );
    }

    /**
     * Get a review searched by its ID, even if the review
     * is deleted.
     *
     * @param $id Review's ID
     * @return ReviewResource
     */
    public function show($id)
    {
        return new ReviewResource(
            Review::withTrashed()->findOrFail($id)
        );
    }
}
