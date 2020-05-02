<?php

namespace App\Http\Middleware;

use Session;
use Closure;
use App\Service\Election\CandidateService;
use App\Service\Election\CandidateRegisterGuard;

class CandidateAuth
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
        if(!Session::has($this->guardName()))
            return redirect()->route('candidate.login.page')->withErrors(['您還未登入!']);

        $guard = Session::get($this->guardName());

        if(!$guard->isLogin())
            return redirect()->route('candidate.login.page')->withErrors(['您還未登入!']);

        return $next($request);
    }

    protected function guardName()
    {
        return 'CandidateService_'.sha1(CandidateService::class).'_guard';
    }
}
