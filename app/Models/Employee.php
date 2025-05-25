<?php

namespace App\Models;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    use HasFactory;

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

    public function scopeAllEmployees($query)
    {
        return $query->whereHas('user', function ($q) {
            $q->where('role', Role::EMPLOYEE->value);
        });
    }

    public function scopeTascaEmployees($query, $tascaId)
    {
        return $query->allEmployees()->where('tasca_id', $tascaId);
    }
}
