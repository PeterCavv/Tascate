<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $user = auth()->user();

        $posts = Post::with('user', 'pictures')
            ->withCount('likedByUsers as likes_count')
            ->get()
            ->map(function ($post) use ($user) {
                $post->is_favorite = $user ? $user->likedPosts->contains($post->id) : false;
                return $post;
            });

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

        $this->authorize('create', Post::class);

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
        return redirect()->route('posts.index')
            ->with('toast', [
                'severity' => 'success',
                'summary' => __('messages.toast.created'),
                'detail' => __('messages.toast.post_created'),
            ]);
    }

    public function show (Post $post)
    {

        $post->load('user', 'pictures', 'likedByUsers', 'comments');
        $post->comments->load('user', 'parentComment', 'childComments');
        $post->is_favorite = auth()->user() ? auth()->user()->likedPosts->contains($post->id) : false;
        $post->likedByUsers = $post->likedByUsers->count();

        $post->load([
            'comments' => function ($query) {
                $query->with(['user', 'childComments.user']);
            }
        ]);


        return Inertia::render('Posts/PostShow', [
            'post' => $post,
        ]);
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);

        return Inertia::render('Posts/PostEdit', [
            'post' => $post,
        ]);
    }

    public function update(UpdatePostRequest $request, Post $post)
    {

        $this->authorize('update', $post);

        $validated = $request->validated();

        $post->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
        ]);

        return redirect()->route('posts.show', $post)
            ->with('toast', [
                'severity' => 'success',
                'summary' => __('messages.toast.updated'),
                'detail' => __('messages.toast.post_updated'),
            ]);;
    }

    public function destroy(Post $post)
    {

        $this->authorize('delete', $post);

        $post->delete();

        return redirect()->route('posts.index')
            ->with('toast', [
                'severity' => 'success',
                'summary' => __('messages.toast.deleted'),
                'detail' => __('messages.toast.post_deleted'),
            ]);
    }

    public function toggleLike(Post $post)
    {

        if ($post->likedByUsers()->where('user_id', auth()->id())->exists()) {
            $post->likedByUsers()->detach(auth()->id());
        } else {
            $post->likedByUsers()->attach(auth()->id());
        }

    }

    public function likedPosts()
    {
        $user = auth()->user();

        $posts = $user->likedPosts()->with('user', 'pictures')->withCount('likedByUsers as like_count')->get()->map(function ($post) {
            $post->is_favorite = true;
            return $post;
        });

        return Inertia::render('Posts/LikedPosts', [
            'posts' => $posts,
        ]);
    }
}
