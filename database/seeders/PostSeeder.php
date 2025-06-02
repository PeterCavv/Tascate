<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Traits\HasDataCheck;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    use HasDataCheck;

    public function run(): void
    {
        if ($this->isDataAlreadyGiven(Post::class)) {
            return;
        }

        $posts =Post::factory()->count(10)->create();

        $users = \App\Models\User::all();

        if ($users->count() > 0) {
            $randomPosts = $posts->random(5);
            foreach ($randomPosts as $post) {
                $randomUser = $users->random();
                $post->likedByUsers()->syncWithoutDetaching([$randomUser->id]);
            }
        }
    }
}
