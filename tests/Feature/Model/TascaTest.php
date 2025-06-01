<?php

use App\Enums\Role;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Manager;
use App\Models\Reservation;
use App\Models\Review;
use App\Models\Tasca;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (){
//    $this->tasca = Tasca::factory()->create();
    $this->userTasca = User::factory()->create();
    $this->userTasca->assignRole(Role::TASCA->value);

    $this->tasca = $this->userTasca->tasca()->create([
        'user_id' => $this->userTasca->id,
    ]);
});

it('belongs to a User', function (){
    expect($this->tasca->user)->toBeInstanceOf(User::class);
});

it('has many Employees', function (){
    Employee::factory()->count(3)->create([
        'tasca_id' => $this->tasca->id
    ]);

    expect($this->tasca->employee)->toHaveCount(3);
});

it('has one Manager', function (){
    $manager = Manager::factory()->create([
        'tasca_id' => $this->tasca->id
    ]);

    expect($this->tasca->manager)->toBeInstanceOf(Manager::class)
        ->and(expect($this->tasca->manager->id)->toBe($manager->id));
});

//it('belongs to many Owners', function (){
//    $owners = Owner::factory()->count(2)->create();
//
//    $this->tasca->properties()->attach($owners->pluck('id'));
//
//    expect($this->tasca->properties)->toHaveCount(2)
//        ->and(expect($this->tasca->properties->first())
//            ->toBeInstanceOf(Owner::class));
//});

it('has many Reviews', function () {
    Review::factory()->count(3)->create([
        'tasca_id' => $this->tasca->id,
    ]);

    expect($this->tasca->reviews)->toHaveCount(3);
});

it('has many Reservations', function () {
    Reservation::factory()->count(3)->create([
        'tasca_id' => $this->tasca->id,
    ]);

    expect($this->tasca->reservations)->toHaveCount(3);
});

it('belong to many customers who have marked it as one of their favorite Tasca', function (){
    $customers = Customer::factory()->count(3)->create();

    $this->tasca->favoriteCustomers()->attach($customers->pluck('id'));

    expect($this->tasca->favoriteCustomers)->toHaveCount(3)
        ->and(expect($this->tasca->favoriteCustomers->first())
            ->toBeInstanceOf(Customer::class));
});
