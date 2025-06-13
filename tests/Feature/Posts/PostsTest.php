<?php

use App\Enums\Role;
use App\Models\Post;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\artisan;

uses(RefreshDatabase::class);
beforeEach(function (){
    $this->seed([
        RoleSeeder::class,
    ]);
});

it('Unauthorized user cannot access to a create-post view', function () {

    $response = $this->get(route('posts.create'));

    $response->assertRedirect(route('login'));
});

it('Auth user can access to create-post view', function () {

    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('posts.create'));

    $response->assertStatus(200);

});

it('Unauthorized user cannot post', function () {

    $response = $this->post(route('posts.store'), [
        'title' => 'Test Title',
        'content' => 'Test Content',
    ]);

    $response->assertRedirect(route('login'));
});

it('Auth user can post', function () {

    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('posts.store'), [
        'title' => 'Test Title',
        'content' => 'Test Content',
    ]);

    $response->assertRedirect(route('posts.index'));
    $response->assertSessionHas('toast', [
        'severity' => 'success',
        'summary' => __('messages.toast.created'),
        'detail' => __('messages.toast.post_created'),
    ]);

});

it('Auth user can delete a post', function () {

    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->delete(route('posts.destroy', $post));

    $response->assertRedirect(route('posts.index'))
        ->assertSessionHas('toast', [
            'severity' => 'success',
            'summary' => __('messages.toast.deleted'),
            'detail' => __('messages.toast.post_deleted'),
        ]);

    $this->assertDatabaseMissing('posts', [
        'id' => $post->id,
    ]);

});

it('Auth user can update a post', function () {

    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->patch(route('posts.update', $post), [
        'title' => 'Updated Title',
        'content' => 'Updated Content',
    ]);

    $response->assertRedirect(route('posts.show', $post))
        ->assertSessionHas('toast', [
            'severity' => 'success',
            'summary' => __('messages.toast.updated'),
            'detail' => __('messages.toast.post_updated'),
        ]);
    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'title' => 'Updated Title',
        'content' => 'Updated Content',
    ]);
});

it('A user cannot get into edit-page for another user posts', function () {

    $user = User::factory()->create();
    $user2 = User::factory()->create();
    $user2->assignRole(Role::CUSTOMER->value);


    $post = Post::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user2)->get(route('posts.edit', $post));

    $response->assertStatus(302);
});

it('A user cannot update a post from another user', function () {

    $user = User::factory()->create();
    $user2 = User::factory()->create();
    $user2->assignRole(Role::CUSTOMER->value);

    $post = Post::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user2)->patch(route('posts.update', $post), [
        'title' => 'Updated Title',
        'content' => 'Updated Content',
    ]);

    $response->assertStatus(302);
});

it('A user cannot delete a post from another user', function () {

    $user = User::factory()->create();
    $user2 = User::factory()->create();
    $user2->assignRole(Role::CUSTOMER->value);

    $post = Post::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user2)->delete(route('posts.destroy', $post));

    $response->assertStatus(302);
});

