<?php

namespace Database\Seeders;

use App\Models\Picture;
use App\Traits\HasDataCheck;
use Illuminate\Database\Seeder;

class PictureSeeder extends Seeder
{
    use HasDataCheck;

    public function run(): void
    {
        if ($this->isDataAlreadyGiven(Picture::class)) {
            return;
        }

        Picture::factory()->count(20)->create();
    }
}
