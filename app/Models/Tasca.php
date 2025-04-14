<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tasca extends Model
{
    protected $fillable = [
      'user_id',
      'name',
      'opening_time',
      'closing_time',
      'capacity',
      'menu',
      'address'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function employee()
    {
        return $this->hasMany(Employee::class);
    }

    public function manager()
    {
        return $this->hasOne(Manager::class);
    }

    public function properties()
    {
        return $this->belongsToMany(Property::class, 'properties', 'tasca_id', 'owner_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
