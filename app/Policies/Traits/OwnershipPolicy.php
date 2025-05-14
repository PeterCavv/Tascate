<?php

namespace App\Policies\Traits;

use App\Models\User;

trait OwnershipPolicy
{
    public function canManage(User $user, $model): bool
    {
        if (isset($model->user_id)) {
            return $user->id === $model->user_id || $user->isAdmin();
        }

        if (method_exists($model, 'customer')) {
            return $user->id === $model->customer->user_id || $user->isAdmin();
        }

        if(method_exists($model, 'tasca')) {
            return $user->id === $model->tasca->user_id || $user->isAdmin();
        }

        return false;
    }
}
