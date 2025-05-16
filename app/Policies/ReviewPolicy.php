<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\Tasca;
use App\Models\User;
use App\Traits\OwnershipPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReviewPolicy
{
    use HandlesAuthorization, OwnershipPolicy;

    public function create(User $user, Review $review): bool
    {
        if(!$user->isCustomer() || !$user->customer){
            return false;
        }

        if(!$review->tasca_id){
            return false;
        }

        return !Review::where('customer_id', $user->customer->id)
        ->where('tasca_id', $review->tasca_id)->exists();

    }

    public function delete(User $user, Review $review): bool
    {
        return $this->canManage($user, $review);
    }

    public function update(User $user, Review $review): bool
    {
        return $this->canManage($user, $review);
    }
}
