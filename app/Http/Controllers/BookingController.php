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
        if (!$car->is_available) {
            return redirect()->route('home')->with('error', 'Mobil ini sedang tidak tersedia.');
        }
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
        
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $days = $startDate->diffInDays($endDate) ?: 1; // Minimal 1 hari
        $totalPrice = $days * $car->price_per_day;

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'car_id' => $car->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        $adminWa = config('services.fonnte.admin_wa_number');
        $fonnteToken = config('services.fonnte.token');
        
        // 1. Kirim Notifikasi via Fonnte API ke Admin
        $notifMessage = "*[SISTEM RENTAL] PESANAN BARU MASUK*\n\n"
            . "Halo Admin, ada pesanan baru dari pengguna aplikasi:\n\n"
            . "👤 Nama: " . Auth::user()->name . "\n"
            . "📞 No. WA: " . (Auth::user()->phone ?? 'Tidak Ada') . "\n"
            . "🚗 Mobil: " . $car->brand . " " . $car->name . " (" . $car->license_plate . ")\n"
            . "🗓️ Tanggal: " . $startDate->format('d M Y') . " s/d " . $endDate->format('d M Y') . " (" . $days . " Hari)\n"
            . "💰 Total Harga: Rp " . number_format($totalPrice, 0, ',', '.') . "\n\n"
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
            . "Saya ingin mengkonfirmasi pesanan saya dengan ID #" . $booking->id . " untuk penyewaan mobil *" . $car->brand . " " . $car->name . "*.\n\n"
            . "Tgl Sewa: " . $startDate->format('d M Y') . " sampai " . $endDate->format('d M Y') . "\n"
            . "Harga Total: Rp " . number_format($totalPrice, 0, ',', '.') . "\n\n"
            . "Mohon arahannya untuk proses verifikasi dan pembayarannya. Terima kasih.";
        
        $adminWaFormatted = $this->formatNumber($adminWa);
        $waUrl = 'https://api.whatsapp.com/send?phone=' . urlencode($adminWaFormatted) . '&text=' . urlencode($waRedirectText);

        // Mengembalikan view sukses (transisi 3 detik sebelum redirect)
        return view('bookings.success', compact('waUrl', 'booking'));
    }
}
