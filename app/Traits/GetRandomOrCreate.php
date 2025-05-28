<?php

namespace App\Traits;
use Illuminate\Database\Eloquent\Model;


trait GetRandomOrCreate
{
    /**
     * Get a random existing model or create a new one with optional default attributes.
     *
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function getRandomOrCreate(array $attributes = [])
    {
        $existingModel = static::inRandomOrder()->first();

        return $existingModel ?? static::create($attributes);
    }
}

