<?php
// app/Http/Controllers/RoleController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Models\AuditLog;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:roles,name',
            'description' => 'nullable|string',
            'department' => 'nullable|string|max:100',
            'section' => 'nullable|string|max:100',
            'title' => 'nullable|string|max:100',
        ]);

        $role = Role::create($request->all());

        AuditLog::log(session('ams_user_id'), null, 'role_created', 'success', [
            'role_name' => $role->name
        ]);

        return redirect()->route('roles.index')->with('success', 'Role berhasil ditambahkan');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:50|unique:roles,name,' . $id,
            'description' => 'nullable|string',
            'department' => 'nullable|string|max:100',
            'section' => 'nullable|string|max:100',
            'title' => 'nullable|string|max:100',
        ]);

        $role->update($request->all());

        AuditLog::log(session('ams_user_id'), null, 'role_updated', 'success', [
            'role_name' => $role->name
        ]);

        return redirect()->route('roles.index')->with('success', 'Role berhasil diupdate');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $roleName = $role->name;
        $role->delete();

        AuditLog::log(session('ams_user_id'), null, 'role_deleted', 'success', [
            'role_name' => $roleName
        ]);

        return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus');
    }

    public function permissions($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $allPermissions = Permission::all();

        return view('roles.permissions', compact('role', 'allPermissions'));
    }

    public function updatePermissions(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $permissions = $request->input('permissions', []);

        $role->permissions()->sync($permissions);

        AuditLog::log(session('ams_user_id'), null, 'role_permissions_updated', 'success', [
            'role_name' => $role->name,
            'permissions_count' => count($permissions)
        ]);

        return redirect()->route('roles.index')->with('success', 'Permissions berhasil diupdate');
    }
}