<?php
// app/Http/Controllers/UserSystemAccessController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserSystemAccess;
use App\Models\User;
use App\Models\System;
use App\Models\Role;
use App\Models\AuditLog;

class UserSystemAccessController extends Controller
{
    public function index(Request $request)
    {
        $query = UserSystemAccess::with(['user', 'system', 'role'])

            // FIX UTAMA: cegah data orphan (user_id yang tidak ada user)
            ->whereHas('user');

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by system
        if ($request->filled('system_id')) {
            $query->where('system_id', $request->system_id);
        }

        // Final data
        $accesses = $query->orderBy('created_at', 'desc')->paginate(20);

        // Additional data
        $users = User::where('active', 'y')->get();
        $systems = System::where('is_active', 1)->get();

        return view('access.index', compact('accesses', 'users', 'systems'));
    }

    public function create()
    {
        $users = User::where('active', 'y')->get();
        $systems = System::where('is_active', 1)->get();
        $roles = Role::all();

        return view('access.create', compact('users', 'systems', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:user,iduser',
            'system_id' => 'required|exists:systems,id',
            'role_id' => 'required|exists:roles,id',
            'expires_at' => 'nullable|date|after:today',
        ]);

        $exists = UserSystemAccess::where('user_id', $request->user_id)
            ->where('system_id', $request->system_id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'User sudah memiliki akses ke system ini')->withInput();
        }

        $access = UserSystemAccess::create([
            'user_id' => $request->user_id,
            'system_id' => $request->system_id,
            'role_id' => $request->role_id,
            'is_active' => 1,
            'granted_at' => now(),
            'granted_by' => session('ams_user_id'),
            'expires_at' => $request->expires_at,
        ]);

        AuditLog::log(session('ams_user_id'), $access->system_id, 'access_granted', 'success', [
            'user_id' => $access->user_id,
            'system_id' => $access->system_id,
            'role_id' => $access->role_id
        ]);

        return redirect()->route('access.index')->with('success', 'Access berhasil diberikan');
    }

    public function revoke($id)
    {
        $access = UserSystemAccess::findOrFail($id);

        AuditLog::log(session('ams_user_id'), $access->system_id, 'access_revoked', 'success', [
            'user_id' => $access->user_id,
            'system_id' => $access->system_id
        ]);

        $access->delete();

        return back()->with('success', 'Access berhasil dicabut');
    }

    public function toggle($id)
    {
        $access = UserSystemAccess::findOrFail($id);
        $access->is_active = !$access->is_active;
        $access->save();

        $status = $access->is_active ? 'activated' : 'deactivated';

        AuditLog::log(session('ams_user_id'), $access->system_id, "access_{$status}", 'success', [
            'user_id' => $access->user_id,
            'system_id' => $access->system_id
        ]);

        return back()->with('success', 'Status access berhasil diupdate');
    }
}
