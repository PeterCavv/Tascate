<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create()->id,
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
        ];
    }
}
