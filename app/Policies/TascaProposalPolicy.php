<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TascaProposalPolicy
{
    use HandlesAuthorization;

    public function update(User $user): bool
    {
        return $user->isAdmin();
    }
}
