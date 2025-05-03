<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Review\ReviewCollection;
use App\Models\Review;

class ReviewController extends Controller
{
    /**
     * Display a listing of all the existing reviews.
     *
     * @return ReviewCollection
     */
    public function index()
    {
        return new ReviewCollection(
            Review::paginate(10)
        );
    }

    /**
     * Get a review searched by its ID.
     *
     * @param $id Review's ID
     * @return ReviewCollection
     */
    public function show($id)
    {
        return new ReviewCollection(
            Review::findOrFail($id)
        );
    }
}
