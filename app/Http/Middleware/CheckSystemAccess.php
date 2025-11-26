<?php
// app/Http/Middleware/CheckSystemAccess.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UserSystemAccess;
use App\Models\System;
use App\Models\AuditLog;

class CheckSystemAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $systemCode)
    {
        $user = $request->get('ams_user');

        if (!$user) {
            return $this->accessDenied('User not authenticated');
        }

        // Get system
        $system = System::where('system_code', $systemCode)->first();

        if (!$system) {
            return $this->accessDenied('System not found');
        }

        if (!$system->isActive()) {
            AuditLog::log($user->iduser, $system->id, 'access_denied_system_inactive', 'denied');
            return $this->accessDenied('System is not active');
        }

        // Check user has access to this system
        $access = UserSystemAccess::where('user_id', $user->iduser)
            ->where('system_id', $system->id)
            ->first();

        if (!$access) {
            AuditLog::log($user->iduser, $system->id, 'access_denied_no_permission', 'denied');
            return $this->accessDenied('You do not have access to this system');
        }

        if (!$access->isActive()) {
            AuditLog::log($user->iduser, $system->id, 'access_denied_inactive_access', 'denied');
            return $this->accessDenied('Your access to this system is inactive or expired');
        }

        // Attach system and access info to request
        $request->merge(['ams_system' => $system]);
        $request->merge(['ams_access' => $access]);
        $request->merge(['ams_role' => $access->role]);

        return $next($request);
    }

    /**
     * Return access denied response
     */
    private function accessDenied($message)
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