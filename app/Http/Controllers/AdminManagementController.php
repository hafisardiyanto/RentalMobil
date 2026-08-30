<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminManagementController extends Controller
{
    public function index()
    {
        // Hanya owner yang bisa akses controller ini
        $admins = User::where('role', 'admin')->orderBy('created_at', 'desc')->get();
        return view('owner.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('owner.admins.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        return redirect()->route('owner.admins.index')->with('success', 'Akun admin berhasil didaftarkan.');
    }

    public function edit(User $admin)
    {
        if ($admin->role !== 'admin') {
            abort(403, 'Hanya bisa mengedit akun Admin.');
        }
        return view('owner.admins.edit', compact('admin'));
    }

    public function update(Request $request, User $admin)
    {
        if ($admin->role !== 'admin') {
            abort(403, 'Hanya bisa mengedit akun Admin.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $admin->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return redirect()->route('owner.admins.index')->with('success', 'Akun admin berhasil diubah.');
    }

    public function destroy(User $admin)
    {
        if ($admin->role !== 'admin') {
            abort(403, 'Hanya bisa menghapus akun Admin.');
        }

        $admin->delete();

        return redirect()->route('owner.admins.index')->with('success', 'Akun admin telah diberhentikan (dihapus).');
    }
}
