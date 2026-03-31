<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cars = \App\Models\Car::all();
        $admin = \App\Models\User::where('role', 'admin')->first(); // Fallback user
        $today = \Carbon\Carbon::today();

        // Buat user dummy jika perlu
        $user1 = \App\Models\User::firstOrCreate(
            ['email' => 'pelanggan1@gmail.com'],
            ['name' => 'Budi Santoso', 'password' => bcrypt('password'), 'role' => 'user']
        );

        $user2 = \App\Models\User::firstOrCreate(
            ['email' => 'pelanggan2@gmail.com'],
            ['name' => 'Siti Aminah', 'password' => bcrypt('password'), 'role' => 'user']
        );

        if ($cars->count() >= 3) {
            // Skenario 1: Mobil Paling Laku (Sering disewa di masa lalu)
            $popularCar = $cars[0];
            for ($i = 1; $i <= 5; $i++) {
                \App\Models\Booking::create([
                    'user_id' => $user1->id,
                    'car_id' => $popularCar->id,
                    'start_date' => $today->copy()->subDays($i * 5),
                    'end_date' => $today->copy()->subDays(($i * 5) - 2),
                    'total_price' => $popularCar->price_per_day * 2,
                    'status' => 'completed'
                ]);
            }

            // Skenario 2: Mobil sedang Disewa Saat Ini (Active)
            $activeCar = $cars[1];
            \App\Models\Booking::create([
                'user_id' => $user2->id,
                'car_id' => $activeCar->id,
                'start_date' => $today->copy()->subDays(1),
                'end_date' => $today->copy()->addDays(2),
                'total_price' => $activeCar->price_per_day * 3,
                'status' => 'approved'
            ]);
            $activeCar->update(['is_available' => false]);

            // Skenario 3: Reservasi Mendatang (Upcoming)
            $upcomingCar = $cars[2];
            \App\Models\Booking::create([
                'user_id' => $user1->id,
                'car_id' => $upcomingCar->id,
                'start_date' => $today->copy()->addDays(3),
                'end_date' => $today->copy()->addDays(5),
                'total_price' => $upcomingCar->price_per_day * 2,
                'status' => 'pending'
            ]);
        }
    }
}
