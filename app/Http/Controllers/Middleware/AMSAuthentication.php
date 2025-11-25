<?php
// app/Http/Middleware/AMSAuthentication.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UserSession;
use App\Models\User;
use App\Models\AuditLog;

class AMSAuthentication
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. Get token from header or cookie
        $token = $this->getTokenFromRequest($request);

        if (!$token) {
            return $this->unauthorizedResponse('Token not provided');
        }

        // 2. Validate token in database
        $session = UserSession::where('token', $token)->first();

        if (!$session) {
            AuditLog::log(null, null, 'token_invalid', 'failed', ['token' => substr($token, 0, 20)]);
            return $this->unauthorizedResponse('Invalid token');
        }

        // 3. Check if token expired
        if ($session->isExpired()) {
            AuditLog::log($session->user_id, null, 'token_expired', 'failed');
            return $this->unauthorizedResponse('Token expired');
        }

        // 4. Get user
        $user = User::find($session->user_id);

        if (!$user) {
            return $this->unauthorizedResponse('User not found');
        }

        // 5. Check if user is active
        if (!$user->isActive()) {
            AuditLog::log($user->iduser, null, 'access_denied_inactive', 'denied');
            return $this->unauthorizedResponse('Account is inactive');
        }

        // 6. Check if account is locked
        if ($user->isLocked()) {
            AuditLog::log($user->iduser, null, 'access_denied_locked', 'denied', [
                'locked_until' => $user->locked_until
            ]);
            return $this->unauthorizedResponse('Account is locked until ' . $user->locked_until);
        }

        // 7. Update last activity
        $session->updateActivity();

        // 8. Attach user to request
        $request->merge(['ams_user' => $user]);
        $request->merge(['ams_session' => $session]);

        return $next($request);
    }

    /**
     * Get token from request
     */
    private function getTokenFromRequest(Request $request)
    {
        // Try Authorization header first
        $header = $request->header('Authorization');
        if ($header && str_starts_with($header, 'Bearer ')) {
            return substr($header, 7);
        }

        // Try cookie
        if ($request->hasCookie('ams_token')) {
            return $request->cookie('ams_token');
        }

        // Try query parameter
        return $request->query('token');
    }

    /**
     * Return unauthorized response
     */
    private function unauthorizedResponse($message)
    {
        if (request()->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
            ], 401);
        }

        return redirect()->route('ams.login')->with('error', $message);
    }
}