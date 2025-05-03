<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;
    public $fillable = [
        'customer_id',
        'tasca_id',
        'body',
        'rating'
    ];

    /**
     * The 'Customer' that the review belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * The 'Tasca' that the review belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tasca()
    {
        return $this->belongsTo(Tasca::class);
    }
}
