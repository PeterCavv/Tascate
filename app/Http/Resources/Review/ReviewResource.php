<?php

namespace App\Http\Resources\Review;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return $request->has('basic') ?
            $this->basicFormat() :
            $this->detailedFormat();
    }

    /**
     * Format the basic response.
     *
     * @return array
     */
    private function basicFormat(): array
    {
        return [
            'customer' => $this->customer?->user?->name,
            'tasca' => $this->tasca?->name,
            'rating' => $this->rating,
            'body' => $this->body,
        ];
    }

    /**
     * Format the detailed response.
     *
     * @return array
     */
    private function detailedFormat(): array
    {
        return [
            'customer' => [
                'id' => $this->customer?->id,
                'name' => $this->customer?->user?->name,
                'email' => $this->customer?->user?->email,
            ],
            'tasca' => [
                'id' => $this->tasca?->id,
                'name' => $this->tasca?->name,
            ],
            'review' => [
                'id' => $this->id,
                'body' => $this->body,
                'rating' => $this->rating,
            ],
        ];
    }
}
