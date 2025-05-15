<?php

namespace Database\Seeders;

use App\Models\Tasca;
use App\Traits\HasDataCheck;
use Illuminate\Database\Seeder;

class TascaSeeder extends Seeder
{
    use HasDataCheck;

    public function run(): void
    {
        if ($this->isDataAlreadyGiven(Tasca::class)) {
            return;
        }

        Tasca::factory()->count(4)->create();
    }
}
