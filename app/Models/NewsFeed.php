<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsFeed extends Model
{
    use HasFactory;
    protected $fillable = [
            'name', 'feet_type_id', 'vendor_id', 'review', 'tags', 'media_path'
        ];
 // Newsfeed has many likes
    public function likes()
{
    return $this->hasMany(Like::class, 'newsfeed_id'); // specify FK for safety
}


    // Check if a specific user has liked this post
    public function isLikedBy($userId)
{
    return $this->likes()->where('user_id', $userId)->exists();
}

// app/Models/NewsFeed.php
 // ✅ Love relationship
    public function loves()
    {
        return $this->hasMany(Love::class, 'newsfeed_id');
    }

    public function isLovedBy($userId)
    {
        return $this->loves()->where('user_id', $userId)->exists();
    }
// comment 
// app/Models/Newsfeed.php

    public function comments() {
        return $this->hasMany(Comment::class, 'newsfeed_id')->whereNull('parent_id')->latest();
    }





}
