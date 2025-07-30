<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnlyUsersCanReply
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::guard('web')->check()) {
            return $next($request);
        }

        abort(403, 'Only customers can reply.');
    }
}
