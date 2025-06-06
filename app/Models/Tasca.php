<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use \App\Traits\GetRandomOrCreate;


class Tasca extends Model
{
    use HasFactory, GetRandomOrCreate;

    protected $fillable = [
        'user_id',
        'name',
        'address',
        'telephone',
        'reservation',
        'reservation_price',
        'menu',
        'opening_time',
        'closing_time',
        'capacity',
        'cif',
        'picture',
    ];

    protected $casts = [
        'reservation' => 'boolean',
        'opening_time' => 'datetime:H:i',
        'closing_time' => 'datetime:H:i',
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

//    public function properties(): BelongsToMany
//    {
//        return $this->belongsToMany(
//            Owner::class,
//            'properties',
//            'tasca_id',
//            'owner_id'
//        );
//    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function favoriteCustomers(): BelongsToMany
    {
        return $this->belongsToMany(
            Customer::class,
            'favs',
            'tasca_id',
            'customer_id'
        );
    }

    public function getPictureUrlAttribute(): ?string
    {
        return $this->picture ? asset($this->picture) : null;
    }
}
