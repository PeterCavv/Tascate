<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\artisan;

uses(RefreshDatabase::class);
beforeEach(function (){
    artisan('db:seed --class=RoleSeeder');
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

    $response->assertRedirect(route('posts.index'))
        ->assertSessionHas('success', 'Post creado exitosamente.');

});

it('Auth user can delete a post', function () {

    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->delete(route('posts.destroy', $post));

    $response->assertRedirect(route('posts.index'))
        ->assertSessionHas('success', 'Post eliminado exitosamente.');

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
        ->assertSessionHas('success', 'Post actualizado exitosamente.');

    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'title' => 'Updated Title',
        'content' => 'Updated Content',
    ]);
});

it('A user cannot get into edit-page for another user posts', function () {

    $user = User::factory()->create();
    $user2 = User::factory()->create([
        'role' => 'customer'
    ]);

    $post = Post::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user2)->get(route('posts.edit', $post));

    $response->assertStatus(403);
});

it('A user cannot update a post from another user', function () {

    $user = User::factory()->create();
    $user2 = User::factory()->create([
        'role' => 'customer'
    ]);

    $post = Post::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user2)->patch(route('posts.update', $post), [
        'title' => 'Updated Title',
        'content' => 'Updated Content',
    ]);

    $response->assertStatus(403);
});

it('A user cannot delete a post from another user', function () {

    $user = User::factory()->create();
    $user2 = User::factory()->create([
        'role' => 'customer'
    ]);

    $post = Post::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user2)->delete(route('posts.destroy', $post));

    $response->assertStatus(403);
});

