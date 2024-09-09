<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

use App\Models\Post;
use App\Models\Role;
use App\Models\Badge;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

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
        return $this->belongsToMany(Role::class);
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class)->withTimestamps();
    }

    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
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
}