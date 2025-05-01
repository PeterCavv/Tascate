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

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function tasca()
    {
        return $this->belongsTo(Tasca::class);
    }
}
