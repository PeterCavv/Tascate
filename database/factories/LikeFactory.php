<?php

namespace Database\Factories;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LikeFactory extends Factory
{
    protected $model = Like::class;

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
            'user_id' => User::getRandomOrCreate([
                'name' => $this->faker->name(),
                'email' => $this->faker->unique()->safeEmail(),
                'password' => bcrypt('12345678'),
            ])->id,
        ];
    }
}
