<?php

namespace Database\Factories;

use App\Models\Manager;
use App\Models\Tasca;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class ManagerFactory extends Factory
{
    protected $model = Manager::class;

    public function definition(): array
    {
        return [
            'tasca_id' => Tasca::factory()->create()->id,
            'user_id' => User::factory()->create()->id,
        ];
    }
}
