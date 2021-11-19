<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;
use Redirect;

class IsLoginMiddleware
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
        if (Sentinel::check()) {
            return Redirect::to('dashboard');
        } else {
            return $next($request);
        }
    }
}
