<?php

namespace App\Http\Middleware;

use Closure;

class CanAnyMiddleware
{
    public function handle($request, Closure $next, $permissions)
    {
        foreach ($permissions as $permission) {
            if ( $request->user()->can($permission)) {
                return $next($request); // allow
            }
        }

        return false; // deny
    }
}