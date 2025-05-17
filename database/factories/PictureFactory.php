<?php

namespace Database\Factories;

use App\Models\Picture;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Post;

class PictureFactory extends Factory
{
    protected $model = Picture::class;

    public function definition(): array
    {
        return [
            'post_id' => Post::factory(),
            'picture_path' => $this->faker->imageUrl(640, 480, 'cats', true),
        ];
    }
}
