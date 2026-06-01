<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();

        return view('admin.roles-permissions.index', compact('roles', 'permissions'));
    }

    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles',
            'description' => 'nullable|string'
        ]);

        Role::create($request->only(['name', 'description']));

        return redirect()->back()->with('success', 'Role created successfully.');
    }

    public function updateRolePermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role->permissions()->sync($request->permissions ?? []);

        return redirect()->back()->with('success', 'Role permissions updated successfully.');
    }

    public function storePermission(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:permissions',
            'description' => 'nullable|string'
        ]);

        Permission::create($request->only(['name', 'slug', 'description']));

        return redirect()->back()->with('success', 'Permission created successfully.');
    }


public function edit(Role $role)
{
    $permissions = Permission::all()->groupBy('model');
    return view('admin.roles-permissions.edit', compact('role', 'permissions'));
}

public function updatePermissions(Request $request, Role $role)
{
    $request->validate([
        'permissions' => 'nullable|array',
        'permissions.*' => 'exists:permissions,id'
    ]);

    $role->permissions()->sync($request->permissions ?? []);

    return redirect()->route('admin.roles.edit', $role->id)
        ->with('success', 'Permissions updated successfully');
}
}
