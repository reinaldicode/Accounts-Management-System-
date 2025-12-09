<?php

namespace App\Helpers;

use App\Models\UserSession;
use App\Models\UserSystemAccess;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class JWTHelper
{
    /**
     * Check if user has active SSO session for a specific system
     * 
     * Logika:
     * 1. Cek apakah user punya access ke system (di user_system_access)
     * 2. Cek apakah user punya session aktif (di user_sessions)
     *
     * @param int $userId
     * @param int $systemId
     * @return bool
     */
    public static function hasActiveSession($userId, $systemId)
    {
        // 1. Cek apakah user punya access ke system tersebut
        $hasAccess = UserSystemAccess::where('user_id', $userId)
            ->where('system_id', $systemId)
            ->where('is_active', true)
            ->exists();
        
        if (!$hasAccess) {
            return false;
        }
        
        // 2. Cek apakah ada session aktif untuk user ini
        // Session dianggap aktif jika belum expired
        $hasActiveSession = UserSession::where('user_id', $userId)
            ->where('expires_at', '>', Carbon::now())
            ->exists();
        
        return $hasActiveSession;
    }

    /**
     * Get active session details for a user
     *
     * @param int $userId
     * @return UserSession|null
     */
    public static function getActiveSession($userId)
    {
        return UserSession::where('user_id', $userId)
            ->where('expires_at', '>', Carbon::now())
            ->orderBy('last_activity_at', 'desc')
            ->first();
    }

    /**
     * Get all active sessions for a user
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getUserActiveSessions($userId)
    {
        return UserSession::where('user_id', $userId)
            ->where('expires_at', '>', Carbon::now())
            ->orderBy('last_activity_at', 'desc')
            ->get();
    }

    /**
     * Count active sessions for a user
     *
     * @param int $userId
     * @return int
     */
    public static function countActiveSessions($userId)
    {
        return UserSession::where('user_id', $userId)
            ->where('expires_at', '>', Carbon::now())
            ->count();
    }

    /**
     * Revoke all sessions for a user
     * (Set expires_at ke waktu sekarang agar session langsung expired)
     *
     * @param int $userId
     * @return int Number of sessions revoked
     */
    public static function revokeAllUserSessions($userId)
    {
        return UserSession::where('user_id', $userId)
            ->where('expires_at', '>', Carbon::now())
            ->update(['expires_at' => Carbon::now()]);
    }

    /**
     * Revoke specific session by token
     *
     * @param string $token
     * @return bool
     */
    public static function revokeSession($token)
    {
        return UserSession::where('token', $token)
            ->update(['expires_at' => Carbon::now()]);
    }

    /**
     * Clean up expired sessions (untuk maintenance)
     *
     * @return int Number of sessions deleted
     */
    public static function cleanupExpiredSessions()
    {
        // Hapus session yang sudah expired lebih dari 7 hari
        return UserSession::where('expires_at', '<', Carbon::now()->subDays(7))
            ->delete();
    }

    /**
     * Get session statistics
     *
     * @return array
     */
    public static function getSessionStats()
    {
        $total = UserSession::count();
        $active = UserSession::where('expires_at', '>', Carbon::now())->count();
        
        return [
            'total' => $total,
            'active' => $active,
            'expired' => $total - $active
        ];
    }

    /**
     * Get active users count (users with active sessions)
     *
     * @return int
     */
    public static function getActiveUsersCount()
    {
        return UserSession::where('expires_at', '>', Carbon::now())
            ->distinct('user_id')
            ->count('user_id');
    }

    /**
     * Check if a specific token is valid and active
     *
     * @param string $token
     * @return bool
     */
    public static function isTokenValid($token)
    {
        return UserSession::where('token', $token)
            ->where('expires_at', '>', Carbon::now())
            ->exists();
    }

    /**
     * Get user ID from token
     *
     * @param string $token
     * @return int|null
     */
    public static function getUserIdFromToken($token)
    {
        $session = UserSession::where('token', $token)
            ->where('expires_at', '>', Carbon::now())
            ->first();
        
        return $session ? $session->user_id : null;
    }

    /**
     * Update last activity for a session
     *
     * @param string $token
     * @return bool
     */
    public static function updateLastActivity($token)
    {
        return UserSession::where('token', $token)
            ->update(['last_activity_at' => Carbon::now()]);
    }

    /**
     * Get systems that user currently has active access to
     *
     * @param int $userId
     * @return array Array of system IDs
     */
    public static function getUserActiveSystems($userId)
    {
        // Ambil semua system yang user punya access DAN punya session aktif
        if (!self::countActiveSessions($userId)) {
            return [];
        }

        return UserSystemAccess::where('user_id', $userId)
            ->where('is_active', true)
            ->pluck('system_id')
            ->toArray();
    }
}