<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\Picture\StorePictureRequest;
use App\Http\Resources\Picture\PictureResource;
use App\Models\Picture;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;

class PictureController extends Controller
{
    /**
     * Get all pictures for a post.
     * @param Post $post
     * @return AnonymousResourceCollection
     */
    public function index(Post $post): AnonymousResourceCollection
    {
        return PictureResource::collection($post->pictures);
    }

    /**
     * Store a new picture.
     * @param StorePictureRequest $request
     * @param Post $post
     * @return PictureResource
     */
    public function store(StorePictureRequest $request, Post $post): PictureResource
    {
        $path = $request->file('picture')->store('posts', 'public');

        $picture = Picture::create([
            'post_id' => $post->id,
            'picture_path' => $path,
        ]);

        return new PictureResource($picture);
    }

    /**
     * Delete a picture.
     * @param Picture $picture
     * @return JsonResponse
     */
    public function destroy(Picture $picture): JsonResponse
    {
        if ($picture->post->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        Storage::disk('public')->delete($picture->picture_path);
        $picture->delete();

        return response()->json(['message' => 'Picture deleted successfully']);
    }
} 