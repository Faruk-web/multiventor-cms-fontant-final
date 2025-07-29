<?php

// app/Models/Comment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['newsfeed_id', 'user_id', 'comment', 'parent_id'];

    public function newsfeed()
    {
        return $this->belongsTo(Newsfeed::class, 'newsfeed_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Unknown User',
        ]);
    }

     // Replies relationship
            public function replies()
        {
            return $this->hasMany(Comment::class, 'parent_id')->with('user');
        }

 }
