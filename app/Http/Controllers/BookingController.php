<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

use App\Traits\WhatsappTrait;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\BookingConfirmationToOwner;

class BookingController extends Controller
{
    use WhatsappTrait;
    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())->with('car')->orderBy('id', 'desc')->get();
        return view('bookings.index', compact('bookings'));
    }

    public function create(Car $car)
    {
        return view('bookings.create', compact('car'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $car = Car::findOrFail($request->car_id);

        // 1. Validasi Ketersediaan Mobil
        if (!$car->isAvailableBetween($request->start_date, $request->end_date)) {
            return redirect()->back()->with('error', 'Mobil tidak tersedia pada rentang tanggal tersebut.');
        }

        // 2. Kalkulasi Durasi dan Harga
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $days = $startDate->diffInDays($endDate) ?: 1; // Minimal 1 hari

        $hargaPerHari = $car->price_per_day;
        $subtotal = $days * $hargaPerHari;
        $total = $subtotal; // Jika tidak ada diskon/biaya tambahan saat ini

        // 3. Generate Nomor Booking Unik
        $nomorBooking = 'RB-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        // 4. Simpan Booking
        $booking = Booking::create([
            'nomor_booking' => $nomorBooking,
            'user_id' => Auth::id(),
            'car_id' => $car->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'durasi' => $days,
            'harga_per_hari' => $hargaPerHari,
            'subtotal' => $subtotal,
            'total' => $total,
            'status_booking' => 'Menunggu Konfirmasi',
            'status_pembayaran' => 'Belum Bayar'
        ]);

        $adminWa = config('services.fonnte.admin_wa_number');
        $fonnteToken = config('services.fonnte.token');

        // 1. Kirim Notifikasi via Fonnte API ke Admin
        $notifMessage = "*[SISTEM RENTAL] PESANAN BARU MASUK*\n\n"
            . "Halo Admin, ada pesanan baru dari pengguna aplikasi:\n\n"
            . "🔖 No. Booking: " . $nomorBooking . "\n"
            . "👤 Nama: " . Auth::user()->name . "\n"
            . "📞 No. WA: " . (Auth::user()->phone ?? 'Tidak Ada') . "\n"
            . "🚗 Mobil: " . $car->brand . " " . $car->name . " (" . $car->license_plate . ")\n"
            . "🗓️ Tanggal: " . $startDate->format('d M Y') . " s/d " . $endDate->format('d M Y') . " (" . $days . " Hari)\n"
            . "💰 Total Harga: Rp " . number_format($total, 0, ',', '.') . "\n\n"
            . "Harap periksa di Dashboard Web Mimin ya!";

        $adminWa = config('services.fonnte.admin_wa_number');
        $this->sendWhatsapp($adminWa, $notifMessage);

        // 2. Kirim Notifikasi via Email ke Admin
        try {
            $adminEmail = env('ADMIN_EMAIL', 'hafisardiyanto19@gmail.com');
            Mail::to($adminEmail)->send(new BookingConfirmationToOwner($booking));
        } catch (\Exception $e) {
            \Log::error("Gagal mengirim email notifikasi booking: " . $e->getMessage());
        }

        // 3. Persiapkan link WhatsApp Redirect untuk User
        $waRedirectText = "Halo Admin RentalMobil,\n\n"
            . "Saya ingin mengkonfirmasi pesanan saya dengan No Booking *" . $nomorBooking . "* untuk penyewaan mobil *" . $car->brand . " " . $car->name . "*.\n\n"
            . "Tgl Sewa: " . $startDate->format('d M Y') . " sampai " . $endDate->format('d M Y') . "\n"
            . "Harga Total: Rp " . number_format($total, 0, ',', '.') . "\n\n"
            . "Mohon arahannya untuk proses verifikasi dan pembayarannya. Terima kasih.";

        $adminWaFormatted = $this->formatNumber($adminWa);
        $waUrl = 'https://api.whatsapp.com/send?phone=' . urlencode($adminWaFormatted) . '&text=' . urlencode($waRedirectText);

        // Mengembalikan view sukses (transisi 3 detik sebelum redirect)
        return view('bookings.success', compact('waUrl', 'booking'));
    }

    public function paymentForm(Booking $booking)
    {
        // Pastikan hanya pemilik booking yang bisa bayar
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('bookings.payment', compact('booking'));
    }

    public function uploadPayment(Request $request, Booking $booking)
    {
        // Pastikan hanya pemilik booking yang bisa bayar
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120', // maks 5MB
        ]);

        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payments', 'public');
            $url = Storage::url($path);

            $booking->update([
                'payment_proof' => $url,
                'status_pembayaran' => 'Menunggu Verifikasi',
            ]);
        }


        return redirect()->route('bookings.index')->with('success', 'Bukti pembayaran berhasil diunggah! Mohon tunggu verifikasi admin.');
    }

    public function cancelBooking(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        // Hanya bisa dibatalkan jika belum disewa / belum selesai
        if (in_array($booking->status_booking, ['Selesai', 'Sedang Disewa', 'Menunggu Pengembalian', 'Dibatalkan', 'Ditolak'])) {
            return redirect()->back()->with('error', 'Booking ini sudah tidak dapat dibatalkan.');
        }

        $booking->update([
            'status_booking' => 'Dibatalkan'
        ]);

        return redirect()->back()->with('success', 'Booking berhasil dibatalkan secara sepihak.');
    }
}
