<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    public $fillable = [
        'tasca_id',
        'customer_id',
        'reservation_price',
        'reservation_date'
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
