<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use App\Traits\OwnershipPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization, OwnershipPolicy;

    public function create(User $user): bool
    {
        return true; // Cualquier usuario autenticado puede crear posts
    }

    public function update(User $user, Post $post): bool
    {
        return $this->canManage($user, $post);
    }

    public function delete(User $user, Post $post): bool
    {
        return $this->canManage($user, $post);
    }

    public function like(User $user, Post $post): bool
    {
        return !$post->likedByUsers()->where('user_id', $user->id)->exists();
    }

    public function unlike(User $user, Post $post): bool
    {
        return $post->likedByUsers()->where('user_id', $user->id)->exists();
    }
} 