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
        $facilities = \App\Models\Facility::orderBy('name')->get();
        return view('admin.cars.create', compact('facilities'));
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
            'description' => 'nullable|string',
            'seats' => 'required|integer|min:1',
            'luggage' => 'required|integer|min:0',
            'facilities' => 'nullable|array',
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
            'description' => $validated['description'] ?? null,
            'seats' => $validated['seats'],
            'luggage' => $validated['luggage'],
            'facilities' => $validated['facilities'] ?? [],
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
        $facilities = \App\Models\Facility::orderBy('name')->get();
        return view('admin.cars.edit', compact('car', 'facilities'));
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
            'description' => 'nullable|string',
            'seats' => 'required|integer|min:1',
            'luggage' => 'required|integer|min:0',
            'facilities' => 'nullable|array',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePaths = is_array($car->images) ? $car->images : [];
        $firstImagePath = $car->image_path;

        if ($request->hasFile('images')) {
            // Delete old images
            if (is_array($car->images)) {
                foreach ($car->images as $img) {
                    // Cek jika path lokal storage bukan URL external
                    if (str_contains($img, '/storage/')) {
                        Storage::disk('public')->delete(str_replace('/storage/', '', $img));
                    }
                }
            } elseif ($car->image_path && str_contains($car->image_path, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $car->image_path));
            }

            // Replace with new images
            $imagePaths = [];
            foreach ($request->file('images') as $key => $file) {
                $path = $file->store('cars', 'public');
                $url = Storage::url($path);
                $imagePaths[] = $url;

                if ($key === 0) {
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
            'description' => $validated['description'] ?? null,
            'seats' => $validated['seats'],
            'luggage' => $validated['luggage'],
            'facilities' => $validated['facilities'] ?? [],
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
        if ($validated['status_pembayaran'] === 'Lunas') {
            if ($booking->status_booking === 'Menunggu Konfirmasi') {
                $booking->update(['status_booking' => 'Booking Dikonfirmasi']);
            } elseif ($booking->status_booking === 'Menunggu Pelunasan') {
                $booking->update(['status_booking' => 'Selesai', 'tagihan_susulan' => 0]);
            }
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
            'km_akhir' => 'required|integer',
            'bbm_akhir' => 'required|string',
            'kondisi_akhir' => 'required|string',
            'foto_akhir' => 'required|image|max:5120',
        ]);

        $validated['foto_akhir'] = $request->file('foto_akhir')->store('cars/returns', 'public');
        $validated['waktu_pengembalian'] = now();
        $validated['status_booking'] = 'Pemeriksaan'; // Masuk kusta audit

        $booking->update($validated);

        // Update Status Mobil
        if ($booking->car) {
            $booking->car->update(['status_mobil' => 'Pemeriksaan']);
        }

        return redirect()->route('admin.bookings.show', $booking->id)->with('success', 'Mobil dikembalikan ke garasi. Silakan catat Denda/Kerusakan jika ada, lalu Finalisasi Invoice.');
    }

    public function finalizeInvoice(Booking $booking)
    {
        // Panggil helper/sync yang biasa ada di BookingFineController
        // Karena logic deposit udah ada di syncBookingTotals tapi kita tulis ulang simpel disini
        $dendaTelat = $booking->fines()->where('type', 'Denda Terlambat')->sum('amount');
        $biayaRusak = $booking->fines()->whereIn('type', ['Kerusakan', 'Lainnya'])->sum('amount');

        $additional = $dendaTelat + $biayaRusak;
        $total = $booking->subtotal + $additional;

        $tagihanSusulan = 0;
        $status_pembayaran = $booking->status_pembayaran;
        $status_booking = 'Selesai';

        if ($additional > 0) {
            if ($booking->deposit > 0) {
                if ($additional > $booking->deposit) {
                    $tagihanSusulan = $additional - $booking->deposit;
                    $status_pembayaran = 'Belum Lunas';
                    $status_booking = 'Menunggu Pelunasan';
                }
            } else {
                $tagihanSusulan = $additional;
                $status_pembayaran = 'Belum Lunas';
                $status_booking = 'Menunggu Pelunasan';
            }
        } else {
            $status_pembayaran = 'Lunas';
        }

        $booking->update([
            'denda_terlambat' => $dendaTelat,
            'biaya_kerusakan' => $biayaRusak,
            'total' => $total,
            'tagihan_susulan' => $tagihanSusulan,
            'status_pembayaran' => $status_pembayaran,
            'status_booking' => $status_booking,
        ]);

        // Update car status
        if ($booking->car) {
            $carStatus = ($biayaRusak > 0) ? 'Maintenance' : 'Tersedia';
            $booking->car->update(['status_mobil' => $carStatus]);
        }

        return redirect()->route('admin.bookings.index')->with('success', 'Finalisasi Invoice Selesai.');
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

    public function fleetUtilization(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        $cars = Car::with([
            'bookings' => function ($q) use ($startDate, $endDate) {
                $q->whereIn('status_booking', ['Selesai', 'Sedang Disewa', 'Menunggu Pengembalian'])
                    ->where(function ($subq) use ($startDate, $endDate) {
                        $subq->whereBetween('start_date', [$startDate, $endDate])
                            ->orWhereBetween('end_date', [$startDate, $endDate]);
                    });
            },
            'maintenances' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            }
        ])->get();

        // Calculate days span
        $carbonStart = Carbon::parse($startDate);
        $carbonEnd = Carbon::parse($endDate);
        $daysInPeriod = $carbonStart->diffInDays($carbonEnd) ?: 1;

        $fleetStats = [];
        $totalFleetRevenue = 0;
        $totalFleetMaintenance = 0;

        foreach ($cars as $car) {
            $daysActive = 0;
            $carRevenue = 0;

            foreach ($car->bookings as $booking) {
                // Calculate overlapping days in period
                $bStart = Carbon::parse($booking->start_date)->max($carbonStart);
                $bEnd = Carbon::parse($booking->end_date)->min($carbonEnd);
                if ($bEnd->greaterThanOrEqualTo($bStart)) {
                    $daysActive += $bStart->diffInDays($bEnd) + 1;
                }

                // Add revenue conservatively based on total minus what hasn't been paid
                $carRevenue += $booking->subtotal; // Base revenue excluding fines (fines can be added if needed)
            }

            $carMaintenance = $car->maintenances->sum('cost');

            $utilizationRate = ($daysActive / $daysInPeriod) * 100;
            $roi = $carRevenue - $carMaintenance;

            $totalFleetRevenue += $carRevenue;
            $totalFleetMaintenance += $carMaintenance;

            $fleetStats[] = [
                'car' => $car,
                'days_active' => $daysActive,
                'utilization_rate' => min($utilizationRate, 100), // Cap at 100%
                'revenue' => $carRevenue,
                'maintenance_cost' => $carMaintenance,
                'net_profit' => $roi
            ];
        }

        // Sort by utilization rate descending
        usort($fleetStats, function ($a, $b) {
            return $b['utilization_rate'] <=> $a['utilization_rate'];
        });

        return view('admin.reports.fleet', compact('fleetStats', 'startDate', 'endDate', 'daysInPeriod', 'totalFleetRevenue', 'totalFleetMaintenance'));
    }

    public function destroyBooking(Booking $booking)
    {
        // Don't need to manually update car availability as it is dynamic now based on date span


        $booking->delete();
        return redirect()->route('admin.bookings.index')->with('success', 'Pesanan berhasil dihapus!');
    }
}
