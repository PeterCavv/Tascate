<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Traits\HasDataCheck;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    use HasDataCheck;

    public function run(): void
    {
        if ($this->isDataAlreadyGiven(Comment::class)) {
            return;
        }

        \Database\Factories\CommentFactory::new()->count(20)->create();
    }
} 