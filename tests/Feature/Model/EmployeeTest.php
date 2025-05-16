<?php


use App\Models\Employee;
use App\Models\Manager;
use App\Models\Tasca;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (){
    $this->employee = Employee::factory()->create();
});

it('belongs to a User', function (){
    expect($this->employee->user)->toBeInstanceOf(User::class);
});

it('belongs to a Tasca', function (){
    expect($this->employee->tasca)->toBeInstanceOf(Tasca::class);
});

it('belongs to a Manager', function (){
    expect($this->employee->manager)->toBeInstanceOf(Manager::class);
});

