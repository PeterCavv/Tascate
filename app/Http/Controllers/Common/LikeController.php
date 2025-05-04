<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Resources\Like\LikeResource;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class LikeController extends Controller
{
    /**
     * Like a post.
     * @param Post $post
     * @return LikeResource
     */
    public function store(Post $post): LikeResource
    {
        $like = Like::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
        ]);

        return new LikeResource($like->load('user'));
    }

    /**
     * Remove a like from a post.
     * @param Post $post
     * @return JsonResponse
     */
    public function destroy(Post $post): JsonResponse
    {
        $like = Like::where('post_id', $post->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($like) {
            $like->delete();
        }

        return response()->json(['message' => 'Like removed successfully']);
    }
} 