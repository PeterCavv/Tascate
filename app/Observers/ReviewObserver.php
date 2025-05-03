<?php

namespace App\Observers;

use App\Models\Review;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class ReviewObserver
{

    /**
     * Handle the Review "creating" event. If the
     * review already exists for the customer and
     * tasca, throw an exception.
     *
     * @param Review $review
     * @return void
     * @throws HttpResponseException
     */
    public function creating(Review $review): void
    {
        if(Review::where('customer_id', $review->customer_id)
            ->where('tasca_id', $review->tasca_id)
            ->exists())
        {
            throw ValidationException::withMessages([
                'name' => 'You cannot update this Review'
            ]);
        }
    }

    /**
     * Handle the Review "updating" event. If the
     * review does not belong to the customer or
     * the user is not an admin,
     * throw an exception.
     *
     * @param Review $review
     * @return void
     * @throws HttpResponseException
     */
    public function updating(Review $review): void
    {
//        if(auth()->user()->id !== $review->customer()->user()->id && auth()->user()->role !== 'admin')
//        {
//            throw ValidationException::withMessages([
//                'name' => 'You cannot update this Review'
//            ]);
//        }

    }

    /**
     * Handle the Review "deleting" event. If the
     * review does not belong to the customer,
     * the user is not an admin or the user is not
     * the tasca owner,
     * throw an exception.
     *
     * @param Review $review
     * @return void
     * @throws HttpResponseException
     */
    public function deleting(Review $review): void
    {
//        if(auth()->user()->id !== $review->customer()->user()->id && auth()->user()->role !== 'admin'
//           && auth()->user()->id !== $review->tasca()->user()->id)
//        {
//            throw ValidationException::withMessages([
//                'name' => 'You cannot delete this Review'
//            ]);
//        }

    }

}
