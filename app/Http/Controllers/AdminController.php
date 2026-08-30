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

        // 1. Total Pendapatan
        $totalRevenue = Booking::whereIn('status_booking', ['Selesai'])->sum('total');

        // 2. Mobil yang Paling Sering Disewa
        $frequentCars = Car::withCount('bookings')
            ->orderByDesc('bookings_count')
            ->take(5)
            ->get();

        // 3. Mobil yang sedang digunakan (Sewa) saat ini
        $activeBookings = Booking::with(['user', 'car'])
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->where('status_booking', 'Sedang Disewa')
            ->get();

        // 4. Mobil yang diBooking untuk di masa depan & menunggu konfirmasi
        $upcomingBookings = Booking::with(['user', 'car'])
            ->whereNotIn('status_booking', ['Selesai', 'Dibatalkan', 'Ditolak'])
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'frequentCars',
            'activeBookings',
            'upcomingBookings'
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
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
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
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
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
        if ($car->bookings()->exists()) {
            return redirect()->route('admin.cars.index')->with('error', 'Gagal: Mobil tidak dapat dihapus karena memiliki histori pemesanan. Silakan ubah status mobil menjadi "Tidak Aktif".');
        }

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
            'status_booking' => 'required|in:Menunggu Konfirmasi,Menunggu Pembayaran,Pembayaran Diverifikasi,Booking Dikonfirmasi,Sedang Disewa,Menunggu Pengembalian,Selesai,Ditolak,Dibatalkan',
        ]);

        $booking->update(['status_booking' => $validated['status_booking']]);

        return redirect()->route('admin.bookings.index')->with('success', 'Status pesanan berhasil diperbarui!');
    }

    public function updatePaymentStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status_pembayaran' => 'required|in:Belum Bayar,Menunggu Verifikasi,Dibayar Sebagian,Lunas',
            'deposit' => 'nullable|integer'
        ]);

        $booking->update([
            'status_pembayaran' => $validated['status_pembayaran'],
            'deposit' => $validated['deposit'] ?? $booking->deposit
        ]);

        // Auto update booking status if Lunas
        if ($validated['status_pembayaran'] === 'Lunas' && $booking->status_booking === 'Menunggu Konfirmasi') {
            $booking->update(['status_booking' => 'Booking Dikonfirmasi']);
        }

        return redirect()->route('admin.bookings.index')->with('success', 'Status Pembayaran & Deposit berhasil diperbarui!');
    }

    public function showBooking(Booking $booking)
    {
        return view('admin.bookings.show', compact('booking'));
    }

    public function processHandover(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'km_awal' => 'nullable|integer',
            'bbm_awal' => 'nullable|string',
            'kondisi_awal' => 'nullable|string',
            'foto_awal' => 'nullable|image',
        ]);

        if ($request->hasFile('foto_awal')) {
            $path = $request->file('foto_awal')->store('operational', 'public');
            $validated['foto_awal'] = Storage::url($path);
        }

        $validated['status_booking'] = 'Sedang Disewa';

        $booking->update($validated);

        // Update Status Mobil
        if ($booking->car) {
            $booking->car->update(['status_mobil' => 'Sedang Disewa']);
        }

        return redirect()->route('admin.bookings.index')->with('success', 'Mobil berhasil diserahterimakan ke Customer.');
    }



    public function processReturn(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'km_akhir' => 'nullable|integer',
            'bbm_akhir' => 'nullable|string',
            'kondisi_akhir' => 'nullable|string',
            'foto_akhir' => 'nullable|image',
            'denda_terlambat' => 'nullable|integer',
            'biaya_kerusakan' => 'nullable|integer',
        ]);

        if ($request->hasFile('foto_akhir')) {
            $path = $request->file('foto_akhir')->store('operational', 'public');
            $validated['foto_akhir'] = Storage::url($path);
        }

        $validated['denda_terlambat'] = $validated['denda_terlambat'] ?? 0;
        $validated['biaya_kerusakan'] = $validated['biaya_kerusakan'] ?? 0;

        $validated['waktu_pengembalian'] = now();
        $validated['status_booking'] = 'Selesai';

        $additional = $validated['denda_terlambat'] + $validated['biaya_kerusakan'];

        // Logika Deposit
        if ($additional > 0) {
            $booking->total += $additional;
            $booking->biaya_tambahan += $additional;

            if ($booking->deposit > 0) {
                if ($additional > $booking->deposit) {
                    $sisaTagihan = $additional - $booking->deposit;
                    $booking->deposit = 0; // Deposit hangus 100%
                    $booking->tagihan_susulan = $sisaTagihan;
                    $booking->status_pembayaran = 'Belum Lunas'; // Rubah bayaran karena ada tunggakan
                } else {
                    $booking->deposit = $booking->deposit - $additional; // Sisa deposit dikembalikan
                }
            } else {
                $booking->tagihan_susulan = $additional;
                $booking->status_pembayaran = 'Belum Lunas';
            }
            $booking->save();
        }

        $booking->update($validated);

        // Update Status Mobil kembali
        if ($booking->car) {
            $booking->car->update(['status_mobil' => 'Tersedia']);
        }

        return redirect()->route('admin.bookings.index')->with('success', 'Mobil berhasil dikembalikan & Kalkulasi Tagihan Selesai.');
    }

    public function reports(Request $request)
    {
        $query = Booking::where('status_booking', 'Selesai')->with(['user', 'car']);

        if ($request->has('start_date') && $request->start_date && $request->has('end_date') && $request->end_date) {
            $query->whereBetween('waktu_pengembalian', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $bookings = $query->orderBy('waktu_pengembalian', 'desc')->get();

        $totalSewaPokok = $bookings->sum('subtotal');
        $totalDenda = $bookings->sum('denda_terlambat');
        $totalKerusakan = $bookings->sum('biaya_kerusakan');
        $totalPendapatan = $totalSewaPokok + $totalDenda + $totalKerusakan;

        return view('admin.reports.index', compact('bookings', 'totalPendapatan', 'totalSewaPokok', 'totalDenda', 'totalKerusakan'));
    }

    public function destroyBooking(Booking $booking)
    {
        // Don't need to manually update car availability as it is dynamic now based on date span


        $booking->delete();
        return redirect()->route('admin.bookings.index')->with('success', 'Pesanan berhasil dihapus!');
    }
}
