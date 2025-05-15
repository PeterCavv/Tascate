<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;
use App\Traits\OwnershipPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReviewPolicy
{
    use HandlesAuthorization, OwnershipPolicy;

    public function create(User $user): bool
    {
        return $user->isCustomer();
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
