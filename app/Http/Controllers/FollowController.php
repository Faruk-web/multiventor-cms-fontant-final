<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class FollowController extends Controller
{
    //
     public function follow(User $user)
    {
        auth()->user()->following()->attach($user->id);
        return back()->with('success', 'You are now following '.$user->name);
    }

    public function unfollow(User $user)
    {
        auth()->user()->following()->detach($user->id);
        return back()->with('success', 'You unfollowed '.$user->name);
    }
}
