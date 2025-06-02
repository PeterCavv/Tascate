<?php

use App\Enums\Role;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\RoleSeeder;
use Database\Seeders\PermissionSeeder;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed([
        RoleSeeder::class,
    ]);

    $this->admin = User::factory()->create();
    $this->admin->assignRole(Role::ADMIN->value);

    $this->customer = User::factory()->create();
    $this->customer->assignRole(Role::CUSTOMER->value);

    $this->post = Post::factory()->create([
        'user_id' => $this->customer->id
    ]);
});

it('allows any authenticated user to create a post', function () {
    expect($this->admin->can('create', Post::class))->toBeTrue()
        ->and($this->customer->can('create', Post::class))->toBeTrue();
});

it('allows post owner to update their post', function () {
    expect($this->customer->can('update', $this->post))->toBeTrue();
});

it('allows admin to update any post', function () {
    expect($this->admin->can('update', $this->post))->toBeTrue();
});

it('denies non-owner and non-admin to update post', function () {
    $otherUser = User::factory()->create();
    $otherUser->assignRole(Role::CUSTOMER->value);
    expect($otherUser->can('update', $this->post))->toBeFalse();
});

it('allows post owner to delete their post', function () {
    expect($this->customer->can('delete', $this->post))->toBeTrue();
});

it('allows admin to delete any post', function () {
    expect($this->admin->can('delete', $this->post))->toBeTrue();
});

it('denies non-owner and non-admin to delete post', function () {
    $otherUser = User::factory()->create();
    $otherUser->assignRole(Role::CUSTOMER->value);
    expect($otherUser->can('delete', $this->post))->toBeFalse();
});

it('allows user to like a post they have not liked', function () {
    expect($this->admin->can('like', $this->post))->toBeTrue();
});

it('denies user to like a post they have already liked', function () {
    $this->post->likedByUsers()->attach($this->admin->id);
    expect($this->admin->can('like', $this->post))->toBeFalse();
});

it('allows user to unlike a post they have liked', function () {
    $this->post->likedByUsers()->attach($this->admin->id);
    expect($this->admin->can('unlike', $this->post))->toBeTrue();
});

it('denies user to unlike a post they have not liked', function () {
    expect($this->admin->can('unlike', $this->post))->toBeFalse();
});
