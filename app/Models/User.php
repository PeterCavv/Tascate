<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function manager()
    {
        return $this->hasOne(Manager::class);
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function tasca()
    {
        return $this->hasOne(Tasca::class);
    }

    public function owner()
    {
        return $this->hasOne(Owner::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Return the user's friends.
     * @return BelongsToMany
     */
    public function friends()
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id_1', 'user_id_2')
            ->wherePivot('status', 'accepted');
    }

    /**
     * Return the user's friend requests.
     * @return BelongsToMany
     */
    public function pendingFriends()
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id_1', 'user_id_2')
            ->wherePivot('status', 'pending');
    }

    /**
     * Return the user's blocked friends.
     * @return BelongsToMany
     */
    public function blockedFriends()
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id_1', 'user_id_2')
            ->wherePivot('status', 'blocked');
    }
}
