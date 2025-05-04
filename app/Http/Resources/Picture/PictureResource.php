<?php

namespace App\Http\Resources\Picture;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PictureResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'post_id' => $this->post_id,
            'url' => $this->picture_path,
            'created_at' => $this->created_at,
        ];
    }
} 