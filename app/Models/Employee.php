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

    public function scopeOneEmployee($query, $employee_id)
    {
        return $query->where('id', $employee_id)
            ->with('user:id,name,email,avatar')
            ->with('tasca:id,name')
            ->with(['manager' => function($query) {
                $query->with('user:id,name,email,avatar');
            }]);
    }
    public function scopeAllEmployees($query)
    {
        return $query->whereHas('user', function ($q) {
            $q->whereHas('roles', function ($roleQuery) {
                $roleQuery->where('name', Role::EMPLOYEE->value);
            });
        })->with('user:id,name,email,avatar');
    }

    public function scopeTascaEmployees($query, $tascaId)
    {
        return $query->allEmployees()->where('tasca_id', $tascaId);
    }

    public function promote(Employee $employee): void
    {
        if($employee->manager_id !== null){
            throw new \Exception('Esta Tasca ya tiene un manager, no puedes tener dos manager a la vez.');
        }
        $employee->user->update(['role' => Role::MANAGER->value]);
    }
}
