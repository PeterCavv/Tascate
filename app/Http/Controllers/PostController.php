<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user', 'pictures')->get();

        return Inertia::render('Posts/PostIndex', [
            'posts' => $posts,
        ]);
    }
    public function create()
    {
        return Inertia::render('Posts/PostForm');
    }

    public function store(PostRequest $request)
    {

        $validated = $request->validated();

        $post = Post::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'user_id' => auth()->id(),
        ]);

        if ($request->hasFile('pictures')){
            foreach ($validated['pictures'] as $picture) {
                $post->pictures()->create([
                    'picture_path' => $picture->store('PicturePosts', 'public'),
                ]);
            }
        }
        return redirect()->route('posts.index')->with('success', 'Post creado exitosamente.');
    }

    public function show (Post $post)
    {

        $post->load('user', 'pictures');

        return Inertia::render('Posts/PostShow', [
            'post' => $post,
        ]);
    }

    public function edit(Post $post)
    {
        return Inertia::render('Posts/PostEdit', [
            'post' => $post,
        ]);
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $validated = $request->validated();

        $post->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
        ]);

        return redirect()->route('posts.show', $post)->with('success', 'Post actualizado exitosamente.');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post eliminado exitosamente.');

    }
}
