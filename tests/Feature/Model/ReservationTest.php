<?php


use App\Models\Customer;
use App\Models\Reservation;
use App\Models\Tasca;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (){
    $this->reservation = Reservation::factory()->create();
});

it('belongs to a Customer', function (){
   expect($this->reservation->customer)->toBeInstanceOf(Customer::class);
});

it('belongs to a Tasca', function (){
    expect($this->reservation->tasca)->toBeInstanceOf(Tasca::class);
});
