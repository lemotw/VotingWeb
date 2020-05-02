<?php

namespace App\Http\Middleware;

use Closure;
use App\Contracts\Utility\UserRole;
use Illuminate\Support\Facades\Auth;

class AdminCheck
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
        if(Auth::user() && Auth::user()->role <= UserRole::admin)
            return $next($request);

        return redirect()->route('admin.login.page')->withErrors(['您無權限訪問該網頁，請嘗試重新登入！']);
    }
}
