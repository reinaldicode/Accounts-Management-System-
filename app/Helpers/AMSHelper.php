<?php
// app/Helpers/AMSHelper.php

namespace App\Helpers;

use App\Models\AmsSetting;
use App\Models\User;
use App\Models\AuditLog;

class AMSHelper
{
    /**
     * Get current authenticated AMS user
     */
    public static function user()
    {
        return request()->get('ams_user');
    }

    /**
     * Get current system
     */
    public static function system()
    {
        return request()->get('ams_system');
    }

    /**
     * Get current role
     */
    public static function role()
    {
        return request()->get('ams_role');
    }

    /**
     * Check if user has permission
     */
    public static function can($permission)
    {
        $role = self::role();
        return $role ? $role->hasPermission($permission) : false;
    }

    /**
     * Check if user cannot perform action
     */
    public static function cannot($permission)
    {
        return !self::can($permission);
    }

    /**
     * Get AMS setting
     */
    public static function setting($key, $default = null)
    {
        return AmsSetting::get($key, $default);
    }

    /**
     * Set AMS setting
     */
    public static function setSetting($key, $value, $type = 'string')
    {
        return AmsSetting::set($key, $value, $type);
    }

    /**
     * Log audit trail
     */
    public static function log($action, $status = 'success', $additionalData = null)
    {
        $user = self::user();
        $system = self::system();

        return AuditLog::log(
            $user?->iduser,
            $system?->id,
            $action,
            $status,
            $additionalData
        );
    }

    /**
     * Generate random API key
     */
    public static function generateApiKey()
    {
        return hash('sha256', uniqid(rand(), true));
    }

    /**
     * Check if account is locked
     */
    public static function isAccountLocked($username)
    {
        $user = User::where('username', $username)->first();
        return $user ? $user->isLocked() : false;
    }

    /**
     * Get max login attempts from settings
     */
    public static function getMaxLoginAttempts()
    {
        return self::setting('max_login_attempts', 5);
    }

    /**
     * Get lockout duration from settings
     */
    public static function getLockoutDuration()
    {
        return self::setting('account_lockout_duration', 30);
    }

    /**
     * Get token TTL from settings
     */
    public static function getAccessTokenTTL()
    {
        return self::setting('jwt_access_token_ttl', 15);
    }

    public static function getRefreshTokenTTL()
    {
        return self::setting('jwt_refresh_token_ttl', 10080);
    }
}