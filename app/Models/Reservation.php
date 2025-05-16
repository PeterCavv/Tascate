<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    public $fillable = [
        'tasca_id',
        'customer_id',
        'reservation_price',
        'reservation_date'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function tasca(): BelongsTo
    {
        return $this->belongsTo(Tasca::class);
    }
}
