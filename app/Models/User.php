<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\UserObserver;
use \App\Traits\GetRandomOrCreate;



#[ObservedBy([UserObserver::class])]
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, GetRandomOrCreate;

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => Role::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'role'
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

    protected $appends = ['role_name'];

    /**
     * Return if the user is an admin.
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->hasRole(Role::ADMIN->value);
    }

    /**
     * Return if the user is a customer.
     * @return bool
     */
    public function isCustomer(): bool
    {
        return $this->hasRole(Role::CUSTOMER->value);
    }

    /**
     * Return if the user is a tasca.
     * @return bool
     */
    public function isTasca(): bool
    {
        return $this->hasRole(Role::TASCA->value);
    }

    /**
     * Return if the user is an employee.
     * @return bool
     */
    public function isEmployee(): bool
    {
        return $this->hasRole(Role::EMPLOYEE->value);
    }

    /**
     * Return if the user is a manager.
     * @return bool
     */
    public function isManager(): bool
    {
        return $this->hasRole(Role::MANAGER->value);
    }

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

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    public function manager(): HasOne
    {
        return $this->hasOne(Manager::class);
    }

    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class);
    }

    public function tasca(): HasOne
    {
        return $this->hasOne(Tasca::class);
    }

//    public function owner(): HasOne
//    {
//        return $this->hasOne(Owner::class);
//    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Return the user's liked posts.
     * @return BelongsToMany
     */
    public function likedPosts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'likes', 'user_id', 'post_id');
    }

    /**
     * Return the user's comments.
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Return the user's friends.
     * @return BelongsToMany
     */
    public function friends(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id_1', 'user_id_2')
            ->wherePivot('status', 'accepted');
    }

    /**
     * Return the user's friend requests.
     * @return BelongsToMany
     */
    public function pendingFriends(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id_1', 'user_id_2')
            ->wherePivot('status', 'pending');
    }

    /**
     * Return the user's blocked friends.
     * @return BelongsToMany
     */
    public function blockedFriends(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id_1', 'user_id_2')
            ->wherePivot('status', 'blocked');
    }

    /**
     * Get the user's role name.
     *
     * @return string
     */
    public function getRoleNameAttribute(): string
    {
        return $this->roles->first()?->name ?? '';
    }
}
