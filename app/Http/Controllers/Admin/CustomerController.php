<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class CustomerController extends Controller
{
    /**
     * Tampilkan data manajemen pelanggan beserta loyalty poin.
     */
    public function index()
    {
        // Ambil semua pengguna dengan role "user" dan riwayat booking-nya (terutama yang sudah Selesai)
        $customers = User::where('role', 'user')->with([
            'bookings' => function ($q) {
                $q->whereIn('status_booking', ['Selesai', 'Booking Dikonfirmasi', 'Sedang Disewa', 'Menunggu Pengembalian']);
            }
        ])->get();

        $customers->transform(function ($customer) {
            // Kalkulasi booking yang khusus berstatus "Selesai" untuk hitung loyalitas final
            $completedBookings = $customer->bookings->where('status_booking', 'Selesai');

            $customer->total_rentals = $completedBookings->count();
            $customer->total_spent = $completedBookings->sum('total');

            // Logika pemberian 1 Poin per Kelipatan Rp100.000 belanja
            $customer->loyalty_points = floor($customer->total_spent / 100000);

            // Algoritma Jenjang Keanggotaan VIP
            if ($customer->total_rentals >= 10 || $customer->total_spent >= 10000000) {
                $customer->vip_status = 'Gold';
            } elseif ($customer->total_rentals >= 4 || $customer->total_spent >= 3000000) {
                $customer->vip_status = 'Silver';
            } else {
                $customer->vip_status = 'Reguler';
            }

            return $customer;
        });

        // Urutkan dari poin & status VIP tertinggi
        $customers = $customers->sortByDesc('loyalty_points')->values();

        return view('admin.customers.index', compact('customers'));
    }
}
