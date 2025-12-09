<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\System;
use App\Models\UserSystemAccess;
use App\Models\AuditLog;
use App\Models\UserSession;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show dashboard dengan SSO-focused metrics
     */
    public function index()
    {
        $user = session('ams_user');

        // Get SSO-focused statistics
        $stats = [
            'total_users' => User::where('active', 'y')->count(),
            'total_systems' => System::where('is_active', 1)->count(),
            
            // Active SSO Sessions (real-time)
            'active_sessions' => UserSession::where('expires_at', '>', now())
                ->distinct('user_id')
                ->count('user_id'),
            
            // Today's successful logins
            'today_logins' => AuditLog::whereIn('action', ['login_success', 'sso_login'])
                ->whereDate('created_at', today())
                ->count(),
        ];

        // Get user's accessible systems (for Super Admin, show all systems they have access to)
        $userSystems = UserSystemAccess::with(['system', 'role'])
            ->where('user_id', $user->iduser)
            ->where('is_active', 1)
            ->whereHas('system', function($query) {
                $query->where('is_active', 1);
            })
            ->where(function($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->get();

        // Get recent SSO activity (prioritize SSO-related actions)
        $recentActivity = AuditLog::with(['user', 'system'])
            ->whereIn('action', [
                'login_success', 
                'sso_login', 
                'access_granted', 
                'access_revoked',
                'login_failed',
                'account_locked'
            ])
            ->orderBy('created_at', 'desc')
            ->limit(15)
            ->get();

        return view('dashboard.index', compact(
            'user', 
            'stats', 
            'userSystems', 
            'recentActivity'
        ));
    }

    /**
     * Get real-time statistics (untuk AJAX polling jika diperlukan)
     */
    public function realtimeStats()
    {
        return response()->json([
            'active_sessions' => UserSession::where('expires_at', '>', now())
                ->distinct('user_id')
                ->count('user_id'),
            'today_logins' => AuditLog::whereIn('action', ['login_success', 'sso_login'])
                ->whereDate('created_at', today())
                ->count(),
            'timestamp' => now()->toISOString(),
        ]);
    }
}