<?php

namespace Database\Factories;

use App\Enums\ManageStatus;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FriendshipFactory extends Factory
{
    protected $model = Friendship::class;

    public function definition(): array
    {
        $user1 = User::getRandomOrCreate([
            'name' => $this->faker->unique()->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('12345678'),
        ]);
        $user2 = User::getRandomOrCreate([
            'name' => 'Usuario Unico',
            'email' => 'gmail@unico.com',
            'password' => bcrypt('12345678'),
        ]);

        // Ensure we don't create a friendship with the same user
        if ($user2->id === $user1->id) {
            $user2 = User::create([
                'name' => $this->faker->unique()->name(),
                'email' => $this->faker->unique()->safeEmail(),
                'password' => bcrypt('12345678'),
            ]);
        }

        return [
            'user_id_1' => $user1->id,
            'user_id_2' => $user2->id,
        ];
    }
}
