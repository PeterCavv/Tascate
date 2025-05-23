<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Post;

class PostCommentController extends Controller
{
    public function store(CommentRequest $request, Post $post)
    {
        $validated = $request->validated();
        Comment::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);
    }
}
