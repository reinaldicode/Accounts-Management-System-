<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserSession;
use App\Models\UserSystemAccess;
use App\Models\System;

class ApiController extends Controller
{
    /**
     * Handle Handshake: Tukar Token SSO dengan Data User Lengkap
     */
    public function validateToken(Request $request)
    {
        // 1. Ambil System yang me-request (Disuntikkan oleh Middleware ValidateSystemAPIKey)
        $requestingSystem = $request->requesting_system; 

        // 2. Ambil Token dari Client
        $token = $request->input('token');

        if (!$token) {
            return response()->json(['success' => false, 'message' => 'Token required'], 400);
        }

        // 3. Cek apakah Token valid di Database Session AMS
        $session = UserSession::where('token', $token)
                    ->where('expires_at', '>', now()) // Pastikan belum expired
                    ->first();

        if (!$session) {
            return response()->json(['success' => false, 'message' => 'Invalid or Expired Token'], 401);
        }

        // 4. Ambil User Pemilik Token
        $user = $session->user;

        // 5. [CRITICAL] Cek Hak Akses User terhadap System yang Request (Docoline)
        // Kita cari: User ID ini + System ID ini -> Punya akses apa?
        $access = UserSystemAccess::with('role')
                    ->where('user_id', $user->iduser)
                    ->where('system_id', $requestingSystem->id)
                    ->where('is_active', 1)
                    ->first();

        if (!$access) {
            return response()->json([
                'success' => false, 
                'message' => 'User authenticated, but has NO ACCESS to ' . $requestingSystem->system_name
            ], 403);
        }

        // 6. Decode Metadata JSON (Role Spesifik: Approver, Originator, Section QC, dll)
        // Pastikan model UserSystemAccess sudah ada 'casts' => ['access_metadata' => 'array']
        $appSpecificRole = $access->access_metadata ?? []; 

        // 7. Hapus Token (One-Time Use) agar aman & tidak bisa di-replay
        $session->delete();

        // 8. Kirim Data Lengkap ke Docoline
        return response()->json([
            'success' => true,
            'user' => [
                'username'  => $user->username,
                'name'      => $user->firstname . ' ' . $user->lastname,
                'email'     => $user->email_corp,
                'empid'     => $user->empid,
            ],
            'access_control' => [
                'global_role' => $access->role->name, // Admin / Staff
                'permissions' => $appSpecificRole,    // {"state": "approver", "section": "QC"}
            ]
        ]);
    }
}