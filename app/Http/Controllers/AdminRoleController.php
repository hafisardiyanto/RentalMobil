<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminRole;

class AdminRoleController extends Controller
{
    public function index()
    {
        $roles = AdminRole::all();
        return view('owner.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('owner.roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:admin_roles,name',
            'permissions' => 'nullable|array'
        ]);

        AdminRole::create([
            'name' => $request->name,
            'permissions' => $request->permissions ?? []
        ]);

        return redirect()->route('owner.roles.index')->with('success', 'Jabatan Admin baru berhasil dibuat.');
    }

    public function edit(AdminRole $role)
    {
        return view('owner.roles.edit', compact('role'));
    }

    public function update(Request $request, AdminRole $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:admin_roles,name,' . $role->id,
            'permissions' => 'nullable|array'
        ]);

        $role->update([
            'name' => $request->name,
            'permissions' => $request->permissions ?? []
        ]);

        return redirect()->route('owner.roles.index')->with('success', 'Hak Akses Jabatan berhasil diperbarui.');
    }

    public function destroy(AdminRole $role)
    {
        if ($role->users()->count() > 0) {
            return back()->with('error', 'Ops! Jabatan ini masih digunakan oleh Admin. Hapus/pindahkan admin terlebih dahulu.');
        }

        $role->delete();

        return redirect()->route('owner.roles.index')->with('success', 'Jabatan berhasil dihapus.');
    }
}
