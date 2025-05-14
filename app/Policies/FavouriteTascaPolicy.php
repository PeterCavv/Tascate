<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FavouriteTascaPolicy
{
    use HandlesAuthorization;

    public function addFavourite(User $user): bool
    {
        return $user->isCustomer();
    }
}
