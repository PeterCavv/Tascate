<?php

use App\Models\Picture;
use App\Models\Post;

it('belongs to a post', function () {
    $post = Post::factory()->create();
    $picture = Picture::factory()->create(['post_id' => $post->id]);

    expect($picture->post)->toBeInstanceOf(Post::class);
    expect($picture->post->id)->toBe($post->id);
});
