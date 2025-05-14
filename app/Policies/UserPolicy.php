<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Traits\OwnershipPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use OwnershipPolicy, HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isTasca();
    }

    public function delete(User $authUser, User $userToDelete): bool
    {
        return $this->canManage($authUser, $userToDelete);
    }

    public function update(User $authUser, User $userToUpdate): bool
    {
        return $this->canManage($authUser, $userToUpdate);
    }
}
