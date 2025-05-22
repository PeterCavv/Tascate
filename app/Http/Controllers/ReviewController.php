<?php

namespace App\Http\Controllers;

use App\Models\User;

class ReviewController extends Controller
{
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
}
