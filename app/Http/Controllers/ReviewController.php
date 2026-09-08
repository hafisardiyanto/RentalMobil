<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Booking;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000'
        ]);

        $booking = Booking::findOrFail($validated['booking_id']);

        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($booking->status_booking !== 'Selesai') {
            return back()->with('error', 'Hanya pesanan yang telah selesai yang bisa diberi ulasan.');
        }

        if (Review::where('booking_id', $booking->id)->exists()) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk pesanan ini.');
        }

        Review::create([
            'user_id' => auth()->id(),
            'booking_id' => $booking->id,
            'car_id' => $booking->car_id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'is_featured' => false
        ]);

        return back()->with('success', 'Ulasan berhasil disimpan! Terima kasih banyak atas masukan Anda.');
    }
}
