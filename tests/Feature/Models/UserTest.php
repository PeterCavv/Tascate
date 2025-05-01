<?php

use App\Models\User;
use App\Models\Employee;
use App\Models\Manager;
use App\Models\Customer;
use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Tasca;

it('employee relationship', function () {
    $user = User::factory()->create();
    $tasca = Tasca::factory()->create();
    $employee = Employee::factory()->create(['user_id' => $user->id, 'tasca_id' => $tasca->id]);

    expect($user->employee)->toBeInstanceOf(Employee::class);
    expect($user->employee->id)->toBe($employee->id);
});

it('manager relationship', function () {
    $user = User::factory()->create();
    $manager = Manager::factory()->create(['user_id' => $user->id]);

    expect($user->manager)->toBeInstanceOf(Manager::class);
    expect($user->manager->id)->toBe($manager->id);
});

it('customer relationship', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->create(['user_id' => $user->id]);

    expect($user->customer)->toBeInstanceOf(Customer::class);
    expect($user->customer->id)->toBe($customer->id);
});

it('posts relationship', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);

    expect($user->posts)->toHaveCount(1);
    expect($user->posts->first()->id)->toBe($post->id);
});

it('likes relationship', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();
    $like = Like::factory()->create(['user_id' => $user->id, 'post_id' => $post->id]);



    expect($user->likes)->toHaveCount(1);
});

it('comments relationship', function () {
    $user = User::factory()->create();
    $comment = Comment::factory()->create(['user_id' => $user->id]);

    expect($user->comments)->toHaveCount(1);
    expect($user->comments->first()->id)->toBe($comment->id);
});

it('friends relationship', function () {
    $user = User::factory()->create();
    $friend = User::factory()->create();
    $user->friends()->attach($friend->id, ['status' => 'accepted']);

    expect($user->friends)->toHaveCount(1);
    expect($user->friends->first()->id)->toBe($friend->id);
});

it('pending friends relationship', function () {
    $user = User::factory()->create();
    $friend = User::factory()->create();
    $user->pendingFriends()->attach($friend->id, ['status' => 'pending']);

    expect($user->pendingFriends)->toHaveCount(1);
    expect($user->pendingFriends->first()->id)->toBe($friend->id);
});

it('blocked friends relationship', function () {
    $user = User::factory()->create();
    $friend = User::factory()->create();
    $user->blockedFriends()->attach($friend->id, ['status' => 'blocked']);

    expect($user->blockedFriends)->toHaveCount(1);
    expect($user->blockedFriends->first()->id)->toBe($friend->id);
});
