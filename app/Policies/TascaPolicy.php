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

    public function update(User $user, Tasca $tasca): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $tasca->user_id;
    }

}
