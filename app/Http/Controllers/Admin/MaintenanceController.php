<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Maintenance;
use App\Models\Car;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $maintenances = Maintenance::with('car')->orderBy('service_date', 'desc')->get();
        // Cek peringatan next maintenance based on KM
        $cars = Car::all();
        $alerts = [];
        foreach ($cars as $car) {
            $lastMaint = $car->maintenances()->orderBy('created_at', 'desc')->first();
            // Mocking mileage check: if a booking return recorded a massive KM increase, we could check it here.
            // For now, if Status is 'Dalam Proses', it's an alert itself.
            if ($lastMaint && $lastMaint->status === 'Dalam Proses') {
                $alerts[] = $car;
            }
        }

        return view('admin.maintenances.index', compact('maintenances', 'alerts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Car $car)
    {
        return view('admin.maintenances.create', compact('car'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Car $car)
    {
        $request->validate([
            'type' => 'required|string',
            'service_date' => 'required|date',
            'cost' => 'required|numeric|min:0',
            'last_km' => 'nullable|numeric',
            'next_km' => 'nullable|numeric',
            'description' => 'nullable|string',
        ]);

        Maintenance::create([
            'car_id' => $car->id,
            'type' => $request->type,
            'description' => $request->description,
            'service_date' => $request->service_date,
            'cost' => $request->cost,
            'last_km' => $request->last_km,
            'next_km' => $request->next_km,
            'status' => 'Dalam Proses',
        ]);

        // Automatis set status_mobil ke maintenance
        $car->update([
            'is_available' => false,
            'status_mobil' => 'Maintenance'
        ]);

        return redirect()->route('admin.maintenances.index')->with('success', 'Perawatan mobil berhasil ditambahkan dan mobil dinonaktifkan sementara.');
    }

    /**
     * Finish maintenance
     */
    public function update(Request $request, Maintenance $maintenance)
    {
        $maintenance->update([
            'status' => 'Selesai'
        ]);

        // Aktifkan kembali mobil
        $maintenance->car->update([
            'is_available' => true,
            'status_mobil' => 'Tersedia'
        ]);

        return redirect()->route('admin.maintenances.index')->with('success', 'Perawatan selesai, mobil kembali Tersedia.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Maintenance $maintenance)
    {
        $maintenance->delete();
        return back()->with('success', 'Data perawatan dihapus.');
    }
}
