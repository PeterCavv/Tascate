<?php

namespace Tests\Feature\Traits;

use App\Models\User;
use App\Traits\HasDataCheck;
use Illuminate\Foundation\Testing\RefreshDatabase;


uses( RefreshDatabase::class);

beforeEach(function () {
    $this->trait = new class {
        use HasDataCheck;
    };
});

it('returns false when no data exists', function () {
    $result = $this->trait->isDataAlreadyGiven(User::class);
    expect($result)->toBeFalse();
});

it('returns true when data exists', function () {
    User::factory()->create();
    $result = $this->trait->isDataAlreadyGiven(User::class);
    expect($result)->toBeTrue();
});
