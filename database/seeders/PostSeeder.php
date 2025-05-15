<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Traits\HasDataCheck;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    use HasDataCheck;

    public function run(): void
    {
        if ($this->isDataAlreadyGiven(Post::class)) {
            return;
        }

        Post::factory()->count(10)->create();
    }
}
