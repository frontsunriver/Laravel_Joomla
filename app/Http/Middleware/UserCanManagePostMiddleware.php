<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Session;
use Illuminate\Support\Facades\Route;

class UserCanManagePostMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $access_token = $request->bearerToken();
        $user = get_user_by_access_token($access_token);

        if(!user_can_manage_post($user->getUserId())){
            return response([
                'status' => 0,
                'message' => __('Can not access')
            ]);
        }

        return $next($request);
    }
}
