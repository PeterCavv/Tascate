<?php

namespace App\Models;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function tasca()
    {
        return $this->belongsTo(Tasca::class);
    }

    public function scopeAllManagers($query)
    {
        return $query->whereHas('user', function ($q) {
            $q->where('role', Role::MANAGER->value);
        });
    }

    public function scopeTascaManagers($query, $tascaId)
    {
        return $query->allManagers()->where('tasca_id', $tascaId);
    }

    public function demote(Manager $manager): void
    {
        $manager->role = Role::EMPLOYEE->value;
        $manager->save();
    }
}
