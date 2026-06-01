<?php

namespace App\Http\Controllers;

use App\Models\AdminLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\Role;

class AdminLoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins = AdminLogin::with('role')->where('PK_ID','!=',1)->paginate(10);
        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.admins.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'UserName' => 'required|string|max:50|unique:adminlogin',
            'Password' => ['required', 'confirmed', Rules\Password::defaults()],
            'Name' => 'required|string|max:100',
            'Mobile' => 'nullable|string|max:20',
            'Email' => 'nullable|email|max:100',
            'UserType' => 'nullable|string|max:50',
            'role_id' => 'required|exists:roles,id',
            'Isactive' => 'boolean'
        ]);

        $admin = AdminLogin::create([
            'UserName' => $request->UserName,
            'Password' => Hash::make($request->Password),
            'TransPass' => Hash::make($request->Password),
            'Name' => $request->Name,
            'Mobile' => $request->Mobile,
            'Email' => $request->Email,
            'UserType' => $request->UserType ?? 'admin',
            'role_id' => $request->role_id,
            'Isactive' => $request->Isactive ?? true,
            'Timestamp' => now(),
            'LastModify' => now()
        ]);

        return redirect()->route('admin.admin-users.index')
            ->with('success', 'Admin created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(AdminLogin $admin)
    {
        return view('admin.admin-users.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AdminLogin $admin_user)
    {
        $roles = Role::all();
        $admin = $admin_user;

        return view('admin.admins.edit', compact('admin', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, $id)
{
    $admin = AdminLogin::findOrFail($id);

    $request->validate([
        'UserName' => 'required|string|max:50|unique:adminlogin,UserName,' . $id . ',PK_ID',
        'Password' => 'nullable|confirmed',
        'Name' => 'required|string|max:100',
        'Mobile' => 'nullable|string|max:20',
        'Email' => 'nullable|email|max:100',
        'UserType' => 'nullable|string|max:50',
        'role_id' => 'required|exists:roles,id',
        'Isactive' => 'boolean',
    ]);

    $data = [
        'UserName'   => $request->UserName,
        'Name'       => $request->Name,
        'Mobile'     => $request->Mobile,
        'Email'      => $request->Email,
        'UserType'   => $request->UserType,
        'role_id'    => $request->role_id,
        'Isactive'   => $request->Isactive ?? 0,
        'LastModify' => now(),
    ];

    if ($request->filled('Password')) {
        $data['Password']  = Hash::make($request->Password);
        $data['TransPass'] = Hash::make($request->Password);
    }

    $admin->update($data);

    return redirect()->route('admin.admin-users.index')
                     ->with('success', 'Admin updated successfully');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdminLogin $admin)
    {
        $admin->delete();
        return redirect()->route('admin.admin-users.index')
            ->with('success', 'Admin deleted successfully');
    }
}
