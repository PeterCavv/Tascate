<?php

use App\Models\Friendship;
use App\Models\User;

it('belongs to user1', function () {
    $user1 = User::factory()->create();
    $friendship = Friendship::factory()->create(['user_id_1' => $user1->id]);

    expect($friendship->user1)->toBeInstanceOf(User::class);
    expect($friendship->user1->id)->toBe($user1->id);
});

it('belongs to user2', function () {
    $user2 = User::factory()->create();
    $friendship = Friendship::factory()->create(['user_id_2' => $user2->id]);

    expect($friendship->user2)->toBeInstanceOf(User::class);
    expect($friendship->user2->id)->toBe($user2->id);
});
