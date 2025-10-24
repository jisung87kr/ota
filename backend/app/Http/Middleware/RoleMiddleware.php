<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', '로그인이 필요합니다.');
        }

        $user = auth()->user();

        // Convert string roles to Role enums
        $allowedRoles = array_map(function ($role) {
            return Role::from($role);
        }, $roles);

        // Check if user has one of the allowed roles
        if (!$user->hasAnyRole($allowedRoles)) {
            abort(403, '접근 권한이 없습니다.');
        }

        // Check if user is active
        if (!$user->isActive()) {
            abort(403, '계정이 활성화되지 않았습니다. 관리자 승인을 기다려주세요.');
        }

        return $next($request);
    }
}
