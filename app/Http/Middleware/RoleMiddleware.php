<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Enforce role-based access control on protected routes.
     *
     * Usage in routes:
     *   ->middleware('role:admin')
     *   ->middleware('role:user')
     *
     * If the authenticated user's role is not in the allowed list,
     * they are redirected to their own role-appropriate dashboard.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  One or more roles permitted to access this route
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = Auth::user();

        // Unauthenticated: redirect to login
        if (!$user) {
            return redirect()->route('login');
        }

        // Role is permitted: continue to the controller
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Role not permitted: redirect to the correct dashboard for their role
        // Named routes: 'user.dashboard' for users, 'admin.dashboard' for admins
        return redirect()
            ->route($user->dashboardRoute())
            ->with('error', 'You do not have permission to access that page.');
    }
}
