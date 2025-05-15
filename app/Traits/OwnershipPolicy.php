<?php

namespace App\Traits;

use App\Models\User;

trait OwnershipPolicy
{
    public function canManage(User $user, $model): bool
    {
        // Si el usuario es admin, puede gestionar cualquier modelo
        if ($user->isAdmin()) {
            return true;
        }

        // Si el modelo tiene user_id, verificar si el usuario es el propietario
        if (isset($model->user_id)) {
            return $user->id === $model->user_id;
        }

        // Si el modelo tiene una relación customer, verificar si el usuario es el propietario
        if (method_exists($model, 'customer')) {
            return $user->id === $model->customer->user_id;
        }

        // Si el modelo tiene una relación tasca, verificar si el usuario es el propietario
        if (method_exists($model, 'tasca')) {
            return $user->id === $model->tasca->user_id;
        }

        // Si el modelo tiene una relación owner, verificar si el usuario es el propietario
        if (method_exists($model, 'owner')) {
            return $user->id === $model->owner->user_id;
        }

        return false;
    }
}
