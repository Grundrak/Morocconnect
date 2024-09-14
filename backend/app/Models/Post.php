<?php

namespace App\Models;

use App\Models\Like;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'image',
        'verification_request',
        'user_id', 
        
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function requestVerification($reason)
    {
        $this->verification_request = $reason;
        $this->verification_status = 'pending';
        $this->save();
    }

    public function verify()
    {
        $this->is_verified = true;
        $this->verification_status = 'approved';
        $this->save();
    }
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function rejectVerification()
    {
        $this->verification_status = 'rejected';
        $this->save();
    }
}
