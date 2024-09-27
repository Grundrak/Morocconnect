<?php

namespace App\Models;

use App\Models\Post;
use App\Models\Role;
use App\Models\Badge;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $with = ['roles'];

    protected $fillable = [
        'username',
        'email',
        'password',
        'avatar',
        'bio',
        'location',
        'birthday',
        'name',
        'reputation',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birthday' => 'date',
    ];
    protected $appends = ['avatar_url'];

    public function getAvatarUrlAttribute()
    {
        return $this->avatar ? Storage::url($this->avatar) : null;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id')
                    ->withTimestamps();
    }
    public function hasAnyRole($roles)
    {
        return $this->roles()->whereIn('name', (array) $roles)->exists();
    }
    public function badges()
    {
        return $this->belongsToMany(Badge::class)->withTimestamps();
    }

    public function hasRole($role)
    {
        return $this->roles()
                    ->where('roles.slug', $role)
                    ->exists();
    }

    public function awardBadge(Badge $badge)
    {
        if (!$this->badges->contains($badge->id)) {
            $this->badges()->attach($badge, ['awarded_at' => now()]);
        }
    }

    public function removeBadge(Badge $badge)
    {
        $this->badges()->detach($badge);
    }

    public function incrementReputation($amount)
    {
        $this->increment('reputation', $amount);
    }
    

    public function decrementReputation($amount)
    {
        $this->decrement('reputation', $amount);
        if ($this->reputation < 0) {
            $this->reputation = 0;
            $this->save();
        }
    }


    //  {follow and unfloow systeme}
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'followed_id', 'follower_id');
    }

    public function following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'followed_id');
    }

    public function isFollowing(User $user): bool
    {
        return $this->following()->where('followed_id', $user->id)->exists();
    }
}