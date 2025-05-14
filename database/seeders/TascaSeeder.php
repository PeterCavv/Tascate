<?php

namespace Database\Seeders;

use App\Models\Tasca;
use Illuminate\Database\Seeder;

class TascaSeeder extends Seeder
{
    public function run(): void
    {
        Tasca::factory()->count(4)->create();
    }
}
