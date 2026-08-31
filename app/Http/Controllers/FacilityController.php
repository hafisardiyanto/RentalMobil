<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;

class FacilityController extends Controller
{
    public function index()
    {
        $facilities = Facility::orderBy('name')->get();
        return view('admin.facilities.index', compact('facilities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:facilities,name',
        ]);

        Facility::create($validated);

        return back()->with('success', 'Fasilitas berhasil ditambahkan!');
    }

    public function destroy(Facility $facility)
    {
        $facility->delete();
        return back()->with('success', 'Fasilitas berhasil dihapus!');
    }
}
