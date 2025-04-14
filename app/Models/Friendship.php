<?php

namespace App\Models;

use App\Enums\FriendshipStatus;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Friendship extends Pivot
{
    protected $table = 'friendships';

    protected $casts = [
        'status' => FriendshipStatus::class,
    ];

    protected $fillable = [
        'user_id_1',
        'user_id_2',
        'status'
    ];

    public function user1()
    {
        return $this->belongsTo(User::class, 'user_id_1');
    }

    public function user2()
    {
        return $this->belongsTo(User::class, 'user_id_2');
    }

    // Friendship management

    /**
     * Accept the friendship request and save the status.
     * @return void
     */
    public function accept()
    {
        $this->status = FriendshipStatus::ACCEPTED;
        $this->save();
    }

    /**
     * Block the relationship between users and save the status.
     * @return void
     */
    public function blocked()
    {
        $this->status = FriendshipStatus::BLOCKED;
        $this->save();
    }

    /**
     * Reject the friendship request and delete the relationship.
     * @return void
     */
    public function rejected()
    {
        $this->delete();
    }

    // End Friendship management
}
