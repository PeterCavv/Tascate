<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class PostObserver
{
    public function created(Post $post): void
    {

    }

    public function updated(Post $post): void
    {
    }

    public function deleting(Post $post): void
    {

        if ($post->pictures()){
            foreach ($post->pictures as $picture) {
                if ($picture->picture_path === null) {
                    return;
                }
                Storage::disk('public')->delete($picture->picture_path);
                $picture->delete();
            }
        }
    }

    public function deleted(Post $post): void
    {
    }

    public function restored(Post $post): void
    {
    }
}
