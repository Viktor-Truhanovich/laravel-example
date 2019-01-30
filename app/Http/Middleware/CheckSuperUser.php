<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class CheckSuperUser
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
        if(\Auth::user()->hasRole(User::USER_ROLE_SUPER_USER_NAME)) {
            return $next($request);
        }
       return abort(403);
    }
}
