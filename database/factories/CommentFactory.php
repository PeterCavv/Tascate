<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Customer;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'post_id' => Post::getRandomOrCreate([
                                'user_id' => User::getRandomOrCreate([
                                    'name' => $this->faker->unique()->name(),
                                    'email' => $this->faker->unique()->safeEmail(),
                                    'password' => bcrypt('12345678'),
                                ])->id,
                                'title' => $this->faker->sentence(),
                                'content' => $this->faker->text(200),
            ])->id,
            'user_id' => Customer::factory()->create()->user_id,
            'content' => $this->faker->text(200),
            'id_comment_father' => null,
        ];
    }
}
