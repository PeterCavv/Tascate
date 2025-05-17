<?php

namespace App\Policies;

use App\Models\Tasca;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TascaPolicy
{
    use HandlesAuthorization;

    public function addFavorite(User $user, Tasca $tasca): bool
    {
        if(!$user->isCustomer()) {
            return false;
        }

        return !$user->customer->favoriteTascas()
            ->where('tascas.id', $tasca->id)->exists();

    }

}
