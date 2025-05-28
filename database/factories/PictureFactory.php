<?php

namespace Database\Factories;

use App\Models\Picture;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Post;

class PictureFactory extends Factory
{
    protected $model = Picture::class;

    public function definition(): array
    {
        return [
            'post_id' => Post::getRandomOrCreate([
                'user_id' => User::getRandomOrCreate([
                    'name' => $this->faker->name(),
                    'email' => $this->faker->unique()->safeEmail(),
                        'password' => bcrypt('12345678'),
                ])->id,
                'title' => $this->faker->sentence(),
                'content' => $this->faker->text(200),
            ])->id,
            'picture_path' => $this->faker->imageUrl(640, 480, 'cats', true),
        ];
    }
}
