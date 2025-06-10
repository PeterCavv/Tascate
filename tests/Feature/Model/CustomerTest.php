<?php

use App\Models\Customer;
use App\Models\Reservation;
use App\Models\Review;
use App\Models\Tasca;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (){
    $this->seed([
        \Database\Seeders\RoleSeeder::class,
    ]);
    $this->customer = Customer::factory()->create();
});

it('belongs to a User', function (){
    expect($this->customer->user)->toBeInstanceOf(User::class);
});

it('has many reviews', function (){
    Review::factory()->count(3)->create([
        'customer_id' => $this->customer->id
    ]);

    expect($this->customer->reviews)->toHaveCount(3);
});

it('has many reservations', function (){
    Reservation::factory()->count(3)->create(
        ['customer_id' => $this->customer->id
    ]);

    expect($this->customer->reservations)->toHaveCount(3);
});

it('belong to many Tascas who marked it as one of their favorite', function (){
    $tascas = Tasca::factory()->count(3)->create();

    $this->customer->favoriteTascas()->attach($tascas->pluck('id'));

    expect($this->customer->favoriteTascas)->toHaveCount(3)
        ->and(expect($this->customer->favoriteTascas->first())
            ->toBeInstanceOf(Tasca::class));
});
