<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    protected $fillable = [
        'post_id',
        'picture_path',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
