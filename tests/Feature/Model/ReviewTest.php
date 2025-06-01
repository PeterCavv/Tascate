<?php


use App\Models\Customer;
use App\Models\Review;
use App\Models\Tasca;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (){
    $this->seed([
        \Database\Seeders\RoleSeeder::class,
    ]);
    $this->review = Review::factory()->create();
});

it('belongs to a Customer', function (){
   expect($this->review->customer)->toBeInstanceOf(Customer::class);
});

it('belongs to a Tasca', function (){
    expect($this->review->tasca)->toBeInstanceOf(Tasca::class);
});
