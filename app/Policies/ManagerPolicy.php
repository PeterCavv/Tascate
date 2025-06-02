<?php

namespace App\Policies;

use App\Models\Manager;
use App\Models\User;
use App\Traits\OwnershipPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class ManagerPolicy
{
    use HandlesAuthorization, OwnershipPolicy;

    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isTasca() || $user->isManager();
    }
    public function view(User $user, Manager $manager): bool
    {
        return $user->isAdmin() ||
               ($user->isTasca() && $user->tasca->id === $manager->tasca_id);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isTasca();
    }

    public function update(User $user, Manager $manager): bool
    {
        return $user->isAdmin() ||
               ($user->isTasca() && $user->tasca->id === $manager->tasca_id);
    }

    public function delete(User $user, Manager $manager): bool
    {
        return $user->isAdmin() ||
               ($user->isTasca() && $user->tasca->id === $manager->tasca_id);
    }

    public function demote(User $user, Manager $manager): bool
    {
        return $user->isAdmin() ||
               ($user->isTasca() && $user->tasca->id === $manager->tasca_id);
    }
}
