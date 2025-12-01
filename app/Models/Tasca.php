<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Traits\GetRandomOrCreate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Attributes\Scope;

class Tasca extends Model
{
    use HasFactory;
    use GetRandomOrCreate;

    protected $fillable = [
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

    public function averageRating(): Attribute
    {
        return Attribute::make(
           get: fn () => $this->reviews()->avg('rating')
                ? (int) round($this->reviews()->avg('rating'))
                : 0
        );
    }

    public function isFavorite(?User $user): bool
    {
        if(!$user) {
            return false;
        }
        return $user->customer
            ? $user->customer->favoriteTascas->contains($this->id)
            : false;
    }

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

    #[Scope]
    public function filter(Builder $query, Collection $filters, ?User $user): void
    {
        if (is_array($filters)) {
            $filters = collect($filters);
        }

         // Filtro por search
    $query->when(
        $filters->get('search'),
        fn ($query, $search) =>
            $query->where(fn ($query) =>
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('address', 'like', "%{$search}%")
            )
    );

    // Filtrar por favoritos SOLO si hay usuario
    if ($user && $filters->get('only_favorites')) {
        $query->whereHas('favorites', fn ($q) => $q->where('user_id', $user->id));
    }
    }
}
