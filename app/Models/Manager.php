<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manager extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id'
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
