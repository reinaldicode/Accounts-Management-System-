<?php
// app/Http/Middleware/CheckPermission.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AuditLog;

class CheckPermission
{
    /**
     * Check if user has specific permission for current system
     */
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        $user = $request->get('ams_user');
        $role = $request->get('ams_role');
        $system = $request->get('ams_system');

        if (!$user || !$role) {
            return $this->permissionDenied('Authentication required');
        }

        // Check if role has required permissions
        foreach ($permissions as $permission) {
            if (!$role->hasPermission($permission)) {
                AuditLog::log(
                    $user->iduser, 
                    $system?->id, 
                    'permission_denied', 
                    'denied',
                    ['required_permission' => $permission]
                );
                
                return $this->permissionDenied("Permission denied: {$permission}");
            }
        }

        return $next($request);
    }

    /**
     * Return permission denied response
     */
    private function permissionDenied($message)
    {
        if (request()->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
            ], 403);
        }

        return redirect()->back()->with('error', $message);
    }
}