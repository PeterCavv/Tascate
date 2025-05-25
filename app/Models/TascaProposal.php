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
        'cif_picture_path',
        'owner_name',
        'owner_email',
        'dni',
        'dni_picture_path',
        'status'
    ];

    protected $casts = [
        'status' => ManageStatus::class,
    ];
}
