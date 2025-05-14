<?php

namespace Database\Seeders;

use App\Models\Friendship;
use Illuminate\Database\Seeder;

class FriendshipSeeder extends Seeder
{
    public function run(): void
    {
        Friendship::factory()
            ->count(10)
            ->create();
    }
}
