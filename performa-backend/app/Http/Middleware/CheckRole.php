<?php

namespace App\Http\Middleware;

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
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth('api')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized - Token tidak valid atau sudah expired'
            ], 401);
        }

        $user = auth('api')->user();

        if (!in_array($user->role, $roles)) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden - Anda tidak memiliki akses ke resource ini',
                'required_roles' => $roles,
                'your_role' => $user->role
            ], 403);
        }

        return $next($request);
    }
}
