<?php

namespace App\Policies\Traits;

use App\Models\User;

trait OwnershipPolicy
{
    public function canManage(User $user, $model): bool
    {
        return $user->id === $model->user_id || $user->isAdmin();
    }
}
