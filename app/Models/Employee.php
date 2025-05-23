<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'tasca_id',
        'manager_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tasca(): BelongsTo
    {
        return $this->belongsTo(Tasca::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(Manager::class);
    }
}
