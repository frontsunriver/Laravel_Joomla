<?php

namespace App\Http\Requests;

use RuntimeException;

class ThrottleRequests extends \Illuminate\Routing\Middleware\ThrottleRequests
{

    /**
     * Resolve request signature.
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     *
     * @throws \RuntimeException
     */
    protected function resolveRequestSignature($request)
    {
        if ($user = $request->user()) {
            $code = get_activation_code($user->getUserId());
            return sha1($code);
        }

        if ($route = $request->route()) {
            return sha1($route->getDomain() . '|' . $request->ip());
        }

        throw new RuntimeException('Unable to generate the request signature. Route unavailable.');
    }

}
