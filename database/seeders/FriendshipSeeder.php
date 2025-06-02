<?php

namespace Database\Seeders;

use App\Models\Friendship;
use App\Traits\HasDataCheck;
use Illuminate\Database\Seeder;

class FriendshipSeeder extends Seeder
{
    use HasDataCheck;

    public function run(): void
    {
        if ($this->isDataAlreadyGiven(Friendship::class)) {
            return;
        }

        Friendship::factory()->count(2)->create();
    }
}
