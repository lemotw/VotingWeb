<?php

namespace App\Http\Middleware;

use Closure;
use App\Contracts\Utility\UserRole;
use Illuminate\Support\Facades\Auth;

class VoteTableCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::user() && Auth::user()->role <= UserRole::vote_table)
            return $next($request);

        return redirect()->back()->withErrors(['msg', 'Identity not allow or haven\'t login']);
    }
}
