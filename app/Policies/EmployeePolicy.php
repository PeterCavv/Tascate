<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;
use App\Traits\OwnershipPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeePolicy
{
    use HandlesAuthorization, OwnershipPolicy;


    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isTasca() || $user->isManager();
    }

    public function view(User $user, Employee $employee): bool
    {
        return $user->isAdmin() ||
               ($user->isTasca() && $user->tasca->id === $employee->tasca_id )||
               ($user->isManager() && $user->manager->tasca_id === $employee->tasca_id);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isTasca() || $user->isManager();
    }

    public function update(User $user, Employee $employee): bool
    {
        return $user->isAdmin() ||
               ($user->isTasca() && $user->tasca->id === $employee->tasca_id) ||
               ($user->isManager() && $user->manager->tasca_id === $employee->tasca_id);
    }

    public function delete(User $user, Employee $employee): bool
    {
        return $user->isAdmin() ||
            ($user->isTasca() && $user->tasca->id === $employee->tasca_id)
            || ($user->isManager() && $user->manager->tasca_id === $employee->tasca_id
                && $user->manager->delete_permission);
    }

    public function promote(User $user, Employee $employee): bool
    {
        return $user->isAdmin() ||
            ($user->isTasca() && $user->tasca->id === $employee->tasca_id) ;
    }

    public function demote(User $user, Employee $employee): bool
    {
        return $user->isAdmin() ||
            ($user->isTasca() && $user->tasca->id === $employee->tasca_id) ;
    }
}
