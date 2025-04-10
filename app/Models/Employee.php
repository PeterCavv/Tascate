<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'tasca_id',
        'manager_id'
    ];

    public function manager()
    {
        return $this->belongsTo(Manager::class);
    }
}
