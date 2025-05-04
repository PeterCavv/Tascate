<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Http\Resources\Comment\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CommentController extends Controller
{
    /**
     * Get all comments for a post.
     * @param Post $post
     * @return AnonymousResourceCollection
     */
    public function index(Post $post): AnonymousResourceCollection
    {
        $comments = $post->comments()
            ->whereNull('id_comment_father')
            ->with(['user', 'replies.user'])
            ->get();

        return CommentResource::collection($comments);
    }

    /**
     * Store a new comment.
     * @param StoreCommentRequest $request
     * @param Post $post
     * @return CommentResource
     */
    public function store(StoreCommentRequest $request, Post $post): CommentResource
    {
        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'content' => $request->content,
            'id_comment_father' => $request->id_comment_father,
        ]);

        return new CommentResource($comment->load('user'));
    }

    /**
     * Update a comment.
     * @param UpdateCommentRequest $request
     * @param Comment $comment
     * @return CommentResource
     */
    public function update(UpdateCommentRequest $request, Comment $comment): CommentResource
    {
        $comment->update($request->validated());
        return new CommentResource($comment->load('user'));
    }

    /**
     * Delete a comment.
     * @param Comment $comment
     * @return JsonResponse
     */
    public function destroy(Comment $comment): JsonResponse
    {
        if ($comment->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->delete();
        return response()->json(['message' => 'Comment deleted successfully']);
    }
} 