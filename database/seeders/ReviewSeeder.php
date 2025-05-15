<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Traits\HasDataCheck;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    use HasDataCheck;

    public function run(): void
    {
        if ($this->isDataAlreadyGiven(Review::class)) {
            return;
        }

        Review::factory()->count(20)->create();
    }
}
