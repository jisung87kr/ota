<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            abort(401, '인증이 필요합니다.');
        }

        // Convert string roles to Role enum
        $allowedRoles = array_map(fn($role) => Role::from($role), $roles);

        // Check if user has any of the allowed roles
        foreach ($allowedRoles as $role) {
            if ($request->user()->hasRole($role)) {
                return $next($request);
            }
        }

        abort(403, '접근 권한이 없습니다.');
    }
}
