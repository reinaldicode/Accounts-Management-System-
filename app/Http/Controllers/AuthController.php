<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\AuditLog;
use App\Helpers\AMSHelper;

class AuthController extends Controller
{
    /**
     * Show login page
     */
    public function showLogin(Request $request)
    {
        // Check if already logged in
        if (session()->has('ams_user')) {
            return redirect()->route('dashboard');
        }

        // Get return URL and system code from query params
        $returnUrl = $request->query('return_url');
        $systemCode = $request->query('system');

        return view('auth.login', compact('returnUrl', 'systemCode'));
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = $request->input('username');
        $password = $request->input('password');

        // 1. Find user
        $user = User::where('username', $username)->first();

        if (!$user) {
            AuditLog::log(null, null, 'login_failed', 'failed', [
                'username' => $username,
                'reason' => 'user_not_found'
            ]);

            return back()->with('error', 'Username atau password salah')->withInput();
        }

        // 2. Check if account is locked
        if ($user->isLocked()) {
            AuditLog::log($user->iduser, null, 'login_failed_locked', 'denied');

            return back()->with('error', 'Akun Anda terkunci sampai ' . $user->locked_until->format('d/m/Y H:i'))
                ->withInput();
        }

        // 3. Check if account is active
        if (!$user->isActive()) {
            AuditLog::log($user->iduser, null, 'login_failed_inactive', 'denied');

            return back()->with('error', 'Akun Anda tidak aktif')->withInput();
        }

        // 4. Validate password (plain text comparison)
        if ($user->password !== $password) {
            // Increment failed attempts
            $user->incrementFailedAttempts();

            // Check if should lock account
            $maxAttempts = AMSHelper::getMaxLoginAttempts();
            if ($user->failed_login_attempts >= $maxAttempts) {
                $lockoutDuration = AMSHelper::getLockoutDuration();
                $user->lockAccount($lockoutDuration);

                AuditLog::log($user->iduser, null, 'account_locked', 'denied', [
                    'failed_attempts' => $user->failed_login_attempts,
                    'lockout_duration' => $lockoutDuration
                ]);

                return back()->with('error', 'Akun Anda terkunci selama ' . $lockoutDuration . ' menit karena terlalu banyak percobaan login gagal');
            }

            AuditLog::log($user->iduser, null, 'login_failed_wrong_password', 'failed', [
                'failed_attempts' => $user->failed_login_attempts
            ]);

            return back()->with('error', 'Username atau password salah. Sisa percobaan: ' . ($maxAttempts - $user->failed_login_attempts))
                ->withInput();
        }

        // 5. Login successful
        $user->resetFailedAttempts();
        $user->update(['last_login_at' => now()]);

        // Create session
        session([
            'ams_user' => $user,
            'ams_user_id' => $user->iduser,
            'ams_username' => $user->username,
            'ams_fullname' => trim($user->firstname . ' ' . $user->lastname),
            'ams_login_time' => now(),
        ]);

        AuditLog::log($user->iduser, null, 'login_success', 'success');

        // Redirect to return URL or dashboard
        $returnUrl = $request->input('return_url');
        if ($returnUrl) {
            return redirect($returnUrl);
        }

        return redirect()->route('dashboard');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        $userId = session('ams_user_id');

        if ($userId) {
            AuditLog::log($userId, null, 'logout', 'success');
        }

        // Clear session
        session()->flush();

        return redirect()->route('login')->with('success', 'Anda telah logout');
    }
}