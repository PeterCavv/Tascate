<?php

namespace App\Traits;

trait HasDataCheck
{
    public function isDataAlreadyGiven(string $modelClass): bool
    {
        return $modelClass::count() > 0;
    }
};
