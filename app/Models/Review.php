<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    public $fillable = [
        'customer_id',
        'tasca_id',
        'body',
        'rating'
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
