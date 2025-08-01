<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'newsfeed_id'];

    public function newsfeed()
    {
        return $this->belongsTo(Newsfeed::class, 'newsfeed_id'); // custom FK specified
    }


}
