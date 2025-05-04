<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Resources\Post\PostCollection;
use App\Http\Resources\Post\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    /**
     * Get all posts with pagination.
     * @return PostCollection
     */
    public function index(): PostCollection
    {
        return new PostCollection(Post::with(['user', 'pictures', 'likes', 'comments'])->paginate(10));
    }

    /**
     * Store a newly created post.
     * @param StorePostRequest $request
     * @return PostResource
     */
    public function store(StorePostRequest $request): PostResource
    {
        $post = Post::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return new PostResource($post->load(['user', 'pictures', 'likes', 'comments']));
    }

    /**
     * Get a post by its ID.
     * @param Post $post
     * @return PostResource
     */
    public function show(Post $post): PostResource
    {
        return new PostResource($post->load(['user', 'pictures', 'likes', 'comments']));
    }

    /**
     * Update the specified post.
     * @param UpdatePostRequest $request
     * @param Post $post
     * @return PostResource
     */
    public function update(UpdatePostRequest $request, Post $post): PostResource
    {
        $post->update($request->validated());
        return new PostResource($post->load(['user', 'pictures', 'likes', 'comments']));
    }

    /**
     * Remove the specified post.
     * @param Post $post
     * @return JsonResponse
     */
    public function destroy(Post $post): JsonResponse
    {
        if ($post->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post->delete();
        return response()->json(['message' => 'Post deleted successfully']);
    }
} 