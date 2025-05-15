<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Traits\HasDataCheck;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    use HasDataCheck;

    public function run(): void
    {
        if ($this->isDataAlreadyGiven(Customer::class)) {
            return;
        }

        Customer::factory()->count(10)->create();
    }
}
