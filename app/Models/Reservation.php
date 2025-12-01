<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class Reservation extends Model
{
    use HasFactory;

    public $fillable = [
        'reservation_price',
        'reservation_date',
        'reservation_time',
        'people',
        'observations',
    ];

    #[Scope]
    public function filter(Builder $query, Collection $filters): void
    {
        if (is_array($filters)) {
            $filters = collect($filters);
        }

        $query->when(
            $filters->get('search'), 
            fn ($query, $search) => $query->where(
                fn ($query) =>
                    $query->whereHas('tasca', fn ($query) =>
                        $query->where('name', 'like', '%' . $search . '%')
                    )
                )
            );
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function tasca(): BelongsTo
    {
        return $this->belongsTo(Tasca::class);
    }
}
