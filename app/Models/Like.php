<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    /**
     * Get the parent likeable model (Blog, Post, etc.).
     */
    public function likeable()
    {
        return $this->morphTo();
    }

    /**
     * Optional: Define relationship back to User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
