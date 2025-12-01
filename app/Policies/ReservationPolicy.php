<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;
use App\Traits\OwnershipPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReservationPolicy
{
    use HandlesAuthorization;
    use OwnershipPolicy;

    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isTascaOwner() || $user->isCustomer();
    }

    public function create(User $user): bool
    {
        return $user->isCustomer();
    }

    public function show(User $user, Reservation $reservation)
    {
        return $this->canManage($user, $reservation);
    }

    public function delete(User $user, Reservation $reservation): bool
    {
        return $this->canManage($user, $reservation);
    }

    public function update(User $user, Reservation $reservation): bool
    {
        return $this->canManage($user, $reservation);
    }
}
