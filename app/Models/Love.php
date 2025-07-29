<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Love extends Model
{
    use HasFactory;
      protected $fillable = ['user_id', 'newsfeed_id'];

    // টেবিলের নাম যদি 'love' হয় তাহলে এটা ঠিক
    protected $table = 'love';

    public function newsfeed()
    {
        return $this->belongsTo(Newsfeed::class, 'newsfeed_id');
    }
}
