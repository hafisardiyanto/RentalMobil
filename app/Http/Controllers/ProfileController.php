<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show the user profile form.
     */
    public function showProfile()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    /**
     * Update the user profile (identity and photos).
     */
    public function updateIdentity(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:1000',
            'nik' => 'nullable|string|max:50',
            'ktp_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'sim_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($request->hasFile('ktp_photo')) {
            $path = $request->file('ktp_photo')->store('identity', 'public');
            $validated['ktp_photo'] = Storage::url($path);
        }

        if ($request->hasFile('sim_photo')) {
            $path = $request->file('sim_photo')->store('identity', 'public');
            $validated['sim_photo'] = Storage::url($path);
        }

        $user->update($validated);

        return redirect()->back()->with('success', 'Profil dan Dokumen Identitas berhasil diperbarui!');
    }
}
