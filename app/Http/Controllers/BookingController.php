<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
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

        Booking::create([
            'user_id' => Auth::id(),
            'car_id' => $car->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        return redirect()->route('bookings.index')->with('success', 'Pemesanan Anda berhasil dikirim! Silakan tunggu konfirmasi admin.');
    }
}
