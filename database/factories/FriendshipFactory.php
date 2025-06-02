<?php

namespace Database\Factories;

use App\Enums\ManageStatus;
use App\Models\Customer;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FriendshipFactory extends Factory
{
    protected $model = Friendship::class;

    public function definition(): array
    {
        $user1 = Customer::factory()->create();
        $user2 = Customer::factory()->create();

        // Ensure we don't create a friendship with the same user
        if ($user2->user_id === $user1->user_id) {
            $user2 = Customer::factory()->create();
        }

        return [
            'user_id_1' => $user1->user_id,
            'user_id_2' => $user2->user_id,
        ];
    }
}
