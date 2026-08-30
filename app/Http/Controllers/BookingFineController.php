<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingFine;
use App\Models\FineAuditLog;

class BookingFineController extends Controller
{
    public function store(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'type' => 'required|in:Denda Terlambat,Kerusakan,Lainnya',
            'part_name' => 'nullable|string',
            'description' => 'required|string',
            'amount' => 'required|integer|min:0',
            'photo' => 'nullable|image|max:5120' // 5MB
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('fines', 'public');
        }

        $validated['booking_id'] = $booking->id;
        $validated['created_by'] = auth()->id();

        $fine = BookingFine::create($validated);

        // Audit Log
        FineAuditLog::create([
            'booking_fine_id' => $fine->id,
            'booking_id' => $booking->id,
            'user_id' => auth()->id(),
            'action' => 'Added',
            'details' => "Menambahkan " . $fine->type . " (" . ($fine->part_name ? $fine->part_name : '') . "): " . $fine->description,
            'old_amount' => 0,
            'new_amount' => $fine->amount
        ]);

        $this->syncBookingTotals($booking);

        return back()->with('success', 'Rincian biaya berhasil ditambahkan dan dicatat di Log.');
    }

    public function update(Request $request, BookingFine $fine)
    {
        $validated = $request->validate([
            'amount' => 'required|integer|min:0'
        ]);

        $oldAmount = $fine->amount;
        $newAmount = $validated['amount'];
        $fine->update(['amount' => $newAmount]);

        FineAuditLog::create([
            'booking_fine_id' => $fine->id,
            'booking_id' => $fine->booking_id,
            'user_id' => auth()->id(),
            'action' => 'Modified',
            'details' => "Mengubah biaya " . $fine->type,
            'old_amount' => $oldAmount,
            'new_amount' => $newAmount
        ]);

        $this->syncBookingTotals($fine->booking);

        return back()->with('success', 'Biaya berhasil diubah dan dicatat di Log.');
    }

    public function destroy(BookingFine $fine)
    {
        $booking = $fine->booking;
        $oldAmount = $fine->amount;

        FineAuditLog::create([
            'booking_fine_id' => null, // Since we are deleting it, avoid foreign constraint fail by keeping it null but referencing the generic log
            'booking_id' => $booking->id,
            'user_id' => auth()->id(),
            'action' => 'Deleted',
            'details' => "Menghapus item " . $fine->type . " (" . ($fine->part_name ? $fine->part_name : '') . ") yang bernial Rp " . number_format($oldAmount, 0, ',', '.'),
            'old_amount' => $oldAmount,
            'new_amount' => 0
        ]);

        $fine->delete();
        $this->syncBookingTotals($booking);

        return back()->with('success', 'Rincian biaya berhasil dihapus dan dicatat di Log.');
    }

    private function syncBookingTotals(Booking $booking)
    {
        // Don't auto-calculate total if status_booking == Selesai? Or do we?
        // Actually, if we allow them to edit it even after Selesai, we should recalculate Tagihan Susulan.
        $dendaTelat = $booking->fines()->where('type', 'Denda Terlambat')->sum('amount');
        $biayaRusak = $booking->fines()->whereIn('type', ['Kerusakan', 'Lainnya'])->sum('amount');

        $additional = $dendaTelat + $biayaRusak;
        $total = $booking->subtotal + $additional;

        $tagihanSusulan = 0;
        $status_pembayaran = $booking->status_pembayaran;
        $status_booking = $booking->status_booking;

        if ($additional > 0) {
            if ($booking->deposit > 0) {
                if ($additional > $booking->deposit) {
                    $tagihanSusulan = $additional - $booking->deposit;
                    $status_pembayaran = 'Belum Lunas';
                    if ($status_booking === 'Selesai') {
                        $status_booking = 'Menunggu Pelunasan';
                    }
                }
            } else {
                $tagihanSusulan = $additional;
                $status_pembayaran = 'Belum Lunas';
                if ($status_booking === 'Selesai') {
                    $status_booking = 'Menunggu Pelunasan';
                }
            }
        } else {
            // No additional, so if they paid booking, they are theoretically lunas
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

        // Also update car status
        if ($booking->car && $booking->status_booking === 'Selesai' || $booking->status_booking === 'Menunggu Pelunasan') {
            $carStatus = ($biayaRusak > 0) ? 'Maintenance' : 'Tersedia';
            $booking->car->update(['status_mobil' => $carStatus]);
        }
    }
}
