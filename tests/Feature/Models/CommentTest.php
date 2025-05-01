<?php

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

it('belongs to a post', function () {
    $post = Post::factory()->create();
    $comment = Comment::factory()->create(['post_id' => $post->id]);

    expect($comment->post)->toBeInstanceOf(Post::class);
    expect($comment->post->id)->toBe($post->id);
});

it('belongs to a user', function () {
    $user = User::factory()->create();
    $comment = Comment::factory()->create(['user_id' => $user->id]);

    expect($comment->user)->toBeInstanceOf(User::class);
    expect($comment->user->id)->toBe($user->id);
});
