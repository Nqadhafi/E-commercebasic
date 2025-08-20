<?php

namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        $u = $request->user();
        if (!$u || (!$roles && !$u->role) || ($roles && !in_array($u->role, $roles))) {
            abort(403);
        }
        return $next($request);
    }
}
