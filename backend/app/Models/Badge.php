<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Badge extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'type',
        'requirement',
        'requirement_type',
    ];

    protected $appends = ['icon_url'];

    public function getIconUrlAttribute()
    {
        return $this->icon ? Storage::url($this->icon) : null;
    }
    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}