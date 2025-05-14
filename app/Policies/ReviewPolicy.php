<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Review;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Policies\Traits\OwnershipPolicy;

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
