<?php

use App\Models\Like;
use App\Models\Post;
use App\Models\User;

it('belongs to a post', function () {
    $post = Post::factory()->create();
    $like = Like::factory()->create(['post_id' => $post->id]);

    expect($like->post)->toBeInstanceOf(Post::class);
    expect($like->post->id)->toBe($post->id);
});

it('belongs to a user', function () {
    $user = User::factory()->create();
    $like = Like::factory()->create(['user_id' => $user->id]);

    expect($like->user)->toBeInstanceOf(User::class);
    expect($like->user->id)->toBe($user->id);
});
