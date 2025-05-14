<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Picture;

class PictureSeeder extends Seeder
{
    public function run(): void
    {
        Picture::factory()->count(10)->create();
    }
}
