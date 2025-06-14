<?php

namespace App\Models;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \App\Traits\GetRandomOrCreate;


class Manager extends Model
{
    use HasFactory, GetRandomOrCreate;

    protected $fillable = [
        'user_id',
        'tasca_id'
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

    public function scopeOneManagerByTasca($query, $tasca_id)
    {
        return $query->allManagers()
            ->where('tasca_id', $tasca_id)
            ->with('user:id,name,email,avatar');
    }

    public function scopeOneManager($query, $manager_id)
    {
        return $query->where('id', $manager_id)
            ->with('user:id,name,email,avatar')
            ->with('tasca:id,name');
    }

    public function scopeAllManagers($query)
    {
        return $query->whereHas('user', function ($q) {
            $q->whereHas('roles', function ($roleQuery) {
                $roleQuery->where('name', Role::MANAGER->value);
            });
        })->with('user:id,name,email,avatar');
    }

    public function scopeTascaManagers($query, $tascaId)
    {
        return $query->allManagers()->where('tasca_id', $tascaId);
    }

    public function demote(Manager $manager): void
    {
        $manager->user->assignRole(Role::EMPLOYEE->value);
    }
}
