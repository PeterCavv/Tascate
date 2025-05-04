<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tasca extends Model
{
    use HasFactory;

    protected $fillable = [
      'user_id',
      'name',
      'opening_time',
      'closing_time',
      'capacity',
      'menu',
      'address',
      'reservation',
      'reservation_price',
      'telephone'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function employee(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function manager(): HasOne
    {
        return $this->hasOne(Manager::class);
    }

    public function owners()
    {
        return $this->belongsToMany(Owner::class, 'properties', 'tasca_id', 'owner_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function usersFav(): BelongsToMany
    {
        return $this->belongsToMany(Tasca::class, 'favs', 'tasca_id', 'user_id');
    }
}
