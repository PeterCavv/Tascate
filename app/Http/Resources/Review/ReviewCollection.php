<?php

namespace App\Http\Resources\Review;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;


class ReviewCollection extends ResourceCollection
{
    public function toArray(Request $request): Collection
    {
        return  $this->collection;
    }
}
