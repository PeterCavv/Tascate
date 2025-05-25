<?php

namespace App\Models;

use App\Enums\ManageStatus;
use Illuminate\Database\Eloquent\Model;

class TascaProposal extends Model
{
    protected $fillable = [
        'tasca_name',
        'address',
        'telephone',
        'cif',
        'owner_name',
        'owner_email',
        'dni',
        'status'
    ];

    protected $casts = [
        'status' => ManageStatus::class,
    ];
}
