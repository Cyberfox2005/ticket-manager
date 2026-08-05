<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRbac
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Simplistic check for demo - in production this would query the role_user/permission_role tables
        // with caching (e.g., Spatie Laravel Permission)
        $hasPermission = \DB::table('permissions')
            ->join('permission_role', 'permissions.id', '=', 'permission_role.permission_id')
            ->join('role_user', 'permission_role.role_id', '=', 'role_user.role_id')
            ->where('role_user.user_id', $user->id)
            ->where('permissions.slug', $permission)
            ->exists();

        if (!$hasPermission && $user->email !== 'admin@ticketflow.io') { // Global admin bypass
            return response()->json(['message' => 'Unauthorized. Missing permission: ' . $permission], 403);
        }

        return $next($request);
    }
}
