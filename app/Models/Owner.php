<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Owner extends Model
{
    protected $fillable = [
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tascas()
    {
        return $this->belongsToMany(Tasca::class, 'properties', 'owner_id', 'tasca_id');
    }

}
