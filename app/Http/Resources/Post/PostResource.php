<?php

namespace App\Http\Resources\Post;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return $request->has('basic') ?
            $this->basicFormat() : $this->detailedFormat();
    }

    /**
     * Transform the resource into a detailed array.
     *
     * @return array
     */
    private function detailedFormat(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'pictures' => $this->pictures->map(fn($picture) => [
                'id' => $picture->id,
                'url' => $picture->url,
            ]),
            'likes_count' => $this->likes->count(),
            'comments_count' => $this->comments->count(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * Transform the resource into a basic array.
     *
     * @return array
     */
    private function basicFormat(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'created_at' => $this->created_at,
        ];
    }
} 