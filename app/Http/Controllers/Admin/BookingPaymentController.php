<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BookingPayment;

class BookingPaymentController extends Controller
{
    public function verify(Request $request, BookingPayment $payment)
    {
        $request->validate([
            'status' => 'required|in:Diterima,Ditolak'
        ]);

        $payment->update([
            'status' => $request->status,
            'verified_by' => auth()->user()->name ?? 'Admin',
        ]);

        if ($request->status === 'Diterima') {
            $booking = $payment->booking;

            if ($payment->type === 'Deposit') {
                $booking->deposit += $payment->amount;
            }

            // Calculate Non-Deposit Paid amounts
            $totalPaid = $booking->payments()
                ->where('status', 'Diterima')
                ->whereIn('type', ['DP', 'Pelunasan', 'Lainnya', 'Refund']) // Refund will be negative if we implement it, but for now positive additions.
                ->sum('amount');

            // Set payment status based on totalPaid vs total tags
            if ($totalPaid >= $booking->total) {
                $booking->status_pembayaran = 'Lunas';
                if ($booking->status_booking === 'Menunggu Konfirmasi') {
                    $booking->status_booking = 'Booking Dikonfirmasi';
                }
            } elseif ($totalPaid > 0) {
                $booking->status_pembayaran = 'Dibayar Sebagian';
                if ($booking->status_booking === 'Menunggu Konfirmasi') {
                    $booking->status_booking = 'Booking Dikonfirmasi';
                }
            }

            $booking->save();

            return back()->with('success', 'Pembayaran sebesar Rp ' . number_format($payment->amount, 0, ',', '.') . ' telah DITERIMA dan saldo terupdate.');
        }

        return back()->with('success', 'Pembayaran DITOLAK.');
    }
}
