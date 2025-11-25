<?php
// app/Http/Middleware/CheckUserActive.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserActive
{
    /**
     * Check if user account is active and not locked
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->get('ams_user');

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 401);
        }

        if (!$user->isActive()) {
            return response()->json([
                'success' => false,
                'message' => 'Account is inactive',
            ], 403);
        }

        if ($user->isLocked()) {
            return response()->json([
                'success' => false,
                'message' => 'Account is locked',
                'locked_until' => $user->locked_until,
            ], 403);
        }

        return $next($request);
    }
}