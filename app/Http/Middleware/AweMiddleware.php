<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Session;
use Illuminate\Support\Facades\Route;

class AweMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!file_exists(storage_path('installed'))) {
            $arr_path = [
                'install',
                'install/environment',
                'install/environment/wizard',
                'install/environment/classic',
                'install/requirements',
                'install/permissions',
                'install/database',
                'install/final',
                'install/environment/saveWizard',
                'install/environment/saveClassic',
                'import-demo',
            ];
            if(!in_array($request->path(), $arr_path)) {
                return redirect()->route('LaravelInstaller::welcome');
            }
        }
        return $next($request);
    }
}
