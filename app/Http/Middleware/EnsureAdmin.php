<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || (! $user->hasRole('Admin') && ! $user->isSuperAdmin() && $user->id !== 1)) {
            abort(403, 'Unauthorized access. Only Administrators can access this feature.');
        }

        return $next($request);
    }
}
