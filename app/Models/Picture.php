<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Picture extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'picture_path',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
