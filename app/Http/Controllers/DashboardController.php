<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\System;
use App\Models\UserSystemAccess;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show dashboard
     */
    public function index()
    {
        $user = session('ams_user');

        // Get statistics
        $stats = [
            'total_users' => User::where('active', 'y')->count(),
            'total_systems' => System::where('is_active', 1)->count(),
            'active_sessions' => DB::table('user_sessions')
                ->where('expires_at', '>', now())
                ->count(),
            'today_logins' => AuditLog::where('action', 'login_success')
                ->whereDate('created_at', today())
                ->count(),
        ];

        // Get user's accessible systems
        $userSystems = UserSystemAccess::with(['system', 'role'])
            ->where('user_id', $user->iduser)
            ->where('is_active', 1)
            ->get();

        // Get recent activity
        $recentActivity = AuditLog::with(['user', 'system'])
            ->where('user_id', $user->iduser)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact('user', 'stats', 'userSystems', 'recentActivity'));
    }
}