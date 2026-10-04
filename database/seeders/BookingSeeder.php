<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Car;
use App\Models\Booking;
use App\Models\BookingPayment;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat/Ambil Mobil Brio untuk pencocokan visual
        $brio = Car::firstOrCreate(
            ['license_plate' => 'D 5678 EFG'],
            [
                'brand' => 'Honda',
                'name' => 'Brio RS',
                'type' => 'City Car',
                'year' => '2022',
                'color' => 'Kuning',
                'seats' => 5,
                'luggage' => 2,
                'transmission' => 'Automatic',
                'gas_type' => 'Bensin',
                'price_per_day' => 300000,
                'is_available' => true,
            ]
        );

        // 2. Buat Pengguna
        $user1 = User::firstOrCreate(
            ['email' => 'customer@test.com'],
            ['name' => 'Budi Customer', 'password' => bcrypt('password'), 'role' => 'user', 'phone' => '081234567890']
        );

        $user2 = User::firstOrCreate(
            ['email' => 'pelanggan1@gmail.com'],
            ['name' => 'Budi Santoso', 'password' => bcrypt('password'), 'role' => 'user', 'phone' => '089912341234']
        );

        // --- SKENARIO 1: BATAL (Sesuai Gambar) ---
        $booking1 = Booking::create([
            'nomor_booking' => 'RB-20260901-BTAL',
            'user_id' => $user1->id,
            'car_id' => $brio->id,
            'start_date' => '2026-09-26',
            'end_date' => '2026-09-30',
            'durasi' => 5,
            'harga_per_hari' => 200000,
            'subtotal' => 1000000,
            'total' => 1000000,
            'deposit' => 0,
            'status_booking' => 'Dibatalkan',
            'status_pembayaran' => 'Belum Lunas'
        ]);

        // Pembayaran DP awal sebelum dibatalkan
        BookingPayment::create([
            'booking_id' => $booking1->id,
            'type' => 'DP',
            'amount' => 500000,
            'status' => 'Diterima',
            'payment_method' => 'Transfer BCA'
        ]);

        // --- SKENARIO 2: SEDANG DISEWA (Sesuai Gambar) ---
        $booking2 = Booking::create([
            'nomor_booking' => 'RB-20260831-SEWA',
            'user_id' => $user2->id,
            'car_id' => $brio->id,
            'start_date' => '2026-08-31',
            'end_date' => '2026-09-05',
            'durasi' => 6,
            'harga_per_hari' => 250000,
            'subtotal' => 1500000,
            'total' => 1500000,
            'deposit' => 0,
            'status_booking' => 'Sedang Disewa',
            'status_pembayaran' => 'Belum Lunas'
        ]);

        // Pembayaran DP
        BookingPayment::create([
            'booking_id' => $booking2->id,
            'type' => 'DP',
            'amount' => 750000,
            'status' => 'Diterima',
            'payment_method' => 'Transfer BNI'
        ]);

        $brio->update(['is_available' => false]);
    }
}
