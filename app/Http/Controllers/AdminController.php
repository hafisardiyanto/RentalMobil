<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::today();

        // 1. Total Revenue (Pendapatan Keseluruhan yang status completed atau approved)
        $totalRevenue = Booking::whereIn('status', ['approved', 'completed'])->sum('total_price');

        // 2. Mobil yang Paling Sering Disewa
        $frequentCars = Car::withCount('bookings')
                            ->orderByDesc('bookings_count')
                            ->take(5)
                            ->get();

        // 3. Mobil yang sedang digunakan (Sewa) saat ini
        $activeBookings = Booking::with(['user', 'car'])
                                 ->whereDate('start_date', '<=', $today)
                                 ->whereDate('end_date', '>=', $today)
                                 ->whereIn('status', ['approved', 'pending'])
                                 ->get();

        // 4. Mobil yang diBooking untuk di masa depan
        $upcomingBookings = Booking::with(['user', 'car'])
                                   ->whereDate('start_date', '>', $today)
                                   ->orderBy('start_date', 'asc')
                                   ->take(5)
                                   ->get();

        return view('admin.dashboard', compact(
            'totalRevenue', 'frequentCars', 'activeBookings', 'upcomingBookings'
        ));
    }

    public function index()
    {
        $cars = Car::orderBy('id', 'desc')->get();
        return view('admin.cars.index', compact('cars'));
    }

    public function create()
    {
        return view('admin.cars.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'license_plate' => 'required|string|unique:cars,license_plate',
            'year' => 'required|integer|min:2000|max:'.(date('Y') + 1),
            'price_per_day' => 'required|numeric|min:0',
            'is_available' => 'required|boolean',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePaths = [];
        $firstImagePath = null;

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $key => $file) {
                $path = $file->store('cars', 'public');
                $url = Storage::url($path);
                $imagePaths[] = $url;
                if ($key === 0) {
                    $firstImagePath = $url;
                }
            }
        }

        Car::create([
            'name' => $validated['name'],
            'brand' => $validated['brand'],
            'license_plate' => $validated['license_plate'],
            'year' => $validated['year'],
            'price_per_day' => $validated['price_per_day'],
            'image_path' => $firstImagePath,
            'images' => $imagePaths,
            'is_available' => $validated['is_available'],
        ]);

        return redirect()->route('admin.cars.index')->with('success', 'Mobil baru berhasil ditambahkan!');
    }

    public function show(Car $car)
    {
        return view('admin.cars.show', compact('car'));
    }

    public function edit(Car $car)
    {
        return view('admin.cars.edit', compact('car'));
    }

    public function update(Request $request, Car $car)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'license_plate' => 'required|string|unique:cars,license_plate,' . $car->id,
            'year' => 'required|integer|min:2000|max:'.(date('Y') + 1),
            'price_per_day' => 'required|numeric|min:0',
            'is_available' => 'required|boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePaths = is_array($car->images) ? $car->images : [];
        $firstImagePath = $car->image_path;

        if ($request->hasFile('images')) {
            // Append new images instead of replacing them
            foreach ($request->file('images') as $key => $file) {
                $path = $file->store('cars', 'public');
                $url = Storage::url($path);
                $imagePaths[] = $url;
                
                if (empty($firstImagePath) && count($imagePaths) === 1) {
                    $firstImagePath = $url;
                }
            }
        }

        $car->update([
            'name' => $validated['name'],
            'brand' => $validated['brand'],
            'license_plate' => $validated['license_plate'],
            'year' => $validated['year'],
            'price_per_day' => $validated['price_per_day'],
            'image_path' => $firstImagePath,
            'images' => $imagePaths,
            'is_available' => $validated['is_available'],
        ]);

        return redirect()->route('admin.cars.index')->with('success', 'Data mobil berhasil diperbarui!');
    }

    public function destroy(Car $car)
    {
        if (is_array($car->images)) {
            foreach ($car->images as $img) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $img));
            }
        } elseif ($car->image_path) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $car->image_path));
        }
        $car->delete();
        return redirect()->route('admin.cars.index')->with('success', 'Mobil berhasil dihapus!');
    }

    public function bookingsIndex()
    {
        $bookings = Booking::with(['user', 'car'])->orderBy('id', 'desc')->get();
        return view('admin.bookings.index', compact('bookings'));
    }

    public function updateBookingStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,completed,cancelled',
        ]);

        $booking->update(['status' => $validated['status']]);

        // Logic for car availability
        if (in_array($validated['status'], ['completed', 'cancelled'])) {
            $booking->car->update(['is_available' => true]);
        } elseif ($validated['status'] === 'approved') {
            $booking->car->update(['is_available' => false]);
        }

        return redirect()->route('admin.bookings.index')->with('success', 'Status pesanan berhasil diperbarui!');
    }

    public function destroyBooking(Booking $booking)
    {
        // If the booking being deleted is the one making the car unavailable, make it available again
        if ($booking->status === 'approved' || $booking->status === 'pending') {
             // Optional: Check if there are other active bookings for the same car. 
             // Simplest for now: if this is deleted, the car is likely available.
             $booking->car->update(['is_available' => true]);
        }
        
        $booking->delete();
        return redirect()->route('admin.bookings.index')->with('success', 'Pesanan berhasil dihapus!');
    }
}
