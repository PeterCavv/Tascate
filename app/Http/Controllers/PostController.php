<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
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
}
