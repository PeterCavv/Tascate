<?php

namespace Database\Factories;

use App\Models\Owner;
use App\Models\Property;
use App\Models\Tasca;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use App\Models\User;

class PropertyFactory extends Factory
{
    protected $model = Property::class;

    public function definition(): array
    {
        return [
        ];
    }
}
