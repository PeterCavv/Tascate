<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\OwnershipPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class UserPolicy
{
    use OwnershipPolicy, HandlesAuthorization;

    public function impersonate(User $authUser, User $targetUser)
    {
        return $authUser->isAdmin() && ! $targetUser->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isTasca();
    }

    public function delete(User $authUser, User $userToDelete): bool
    {
        return !$userToDelete->isAdmin()
            && ( $authUser->id === $userToDelete->id
                    || $authUser->isTasca() && $userToDelete->isEmployee() );
    }

    public function update(User $authUser, User $userToUpdate): bool
    {
        return $this->canManage($authUser, $userToUpdate);
    }

}
