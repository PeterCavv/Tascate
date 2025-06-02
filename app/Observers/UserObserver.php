<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserObserver
{
    public function created(User $user): void
    {

    }

    public function updated(User $user): void
    {
    }

    public function deleting(User $user): void
    {
        if ($user->avatar === null) {
            return;
        }
        Storage::disk('public')->delete($user->avatar);
    }

    public function deleted(User $user): void
    {


    }

    public function restored(User $user): void
    {
    }
}
