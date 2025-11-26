<?php
// app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AuditLog;

class UserController extends Controller
{
    /**
     * Display user list
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(20);

        return view('users.index', compact('users'));
    }

    /**
     * Show create user form
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store new user
     */
    public function store(Request $request)
    {
        $request->validate([
            'empid' => 'required|string|max:10|unique:user,empid',
            'username' => 'required|string|max:10|unique:user,username',
            'password' => 'required|string|min:6',
            'firstname' => 'required|string|max:50',
            'middlename' => 'nullable|string|max:50',
            'lastname' => 'nullable|string|max:50',
            'email_corp' => 'nullable|email|max:50',
            'email_personal' => 'nullable|email|max:50',
        ]);

        $user = User::create([
            'empid' => $request->empid,
            'username' => $request->username,
            'password' => $request->password, // Plain text as per requirement
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'slug' => $request->username . '-' . strtolower($request->firstname),
            'email_corp' => $request->email_corp,
            'email_personal' => $request->email_personal,
            'roleid' => 5, // Default: user
            'active' => 'y',
            'created_at' => now(),
        ]);

        AuditLog::log(
            session('ams_user_id'),
            null,
            'user_created',
            'success',
            ['created_user_id' => $user->iduser, 'username' => $user->username]
        );

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Show edit user form
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('users.edit', compact('user'));
    }

    /**
     * Update user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'empid' => 'required|string|max:10|unique:user,empid,' . $id . ',iduser',
            'username' => 'required|string|max:10|unique:user,username,' . $id . ',iduser',
            'firstname' => 'required|string|max:50',
            'middlename' => 'nullable|string|max:50',
            'lastname' => 'nullable|string|max:50',
            'email_corp' => 'nullable|email|max:50',
            'email_personal' => 'nullable|email|max:50',
            'active' => 'required|in:y,n',
        ]);

        $user->update([
            'empid' => $request->empid,
            'username' => $request->username,
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'email_corp' => $request->email_corp,
            'email_personal' => $request->email_personal,
            'active' => $request->active,
            'updated_at' => now(),
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            $user->update(['password' => $request->password]);
        }

        AuditLog::log(
            session('ams_user_id'),
            null,
            'user_updated',
            'success',
            ['updated_user_id' => $user->iduser, 'username' => $user->username]
        );

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diupdate');
    }

    /**
     * Delete user (soft delete)
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting own account
        if ($user->iduser == session('ams_user_id')) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri');
        }

        $username = $user->username;
        $user->delete();

        AuditLog::log(
            session('ams_user_id'),
            null,
            'user_deleted',
            'success',
            ['deleted_user_id' => $id, 'username' => $username]
        );

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus');
    }
}