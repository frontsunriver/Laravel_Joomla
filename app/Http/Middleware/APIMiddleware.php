<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Session;
use Illuminate\Support\Facades\Route;

class APIMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $access_token = $request->bearerToken();
        if (is_null($access_token)) {
            return response([
                'status' => 0,
                'message' => __('Token mismatch')
            ]);
        }
        if (!api_token_valid($access_token)) {
            return response([
                'status' => 0,
                'message' => __('Invalid token')
            ]);
        }

        return $next($request);
    }
}
