<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeding Akun Admin (Pemilik Rental)
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@rentalmobil.com'],
            [
                'name' => 'Administrator Rental',
                'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
                'role' => 'admin'
            ]
        );

        // Seeding Data Kendaraan
        \App\Models\Car::updateOrCreate(
            ['license_plate' => 'B 1234 ABC'],
            [
                'name' => 'Avanza Veloz',
                'brand' => 'Toyota',
                'year' => 2022,
                'price_per_day' => 350000,
                'image_path' => 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&q=80&w=600',
            ]
        );

        \App\Models\Car::updateOrCreate(
            ['license_plate' => 'D 5678 EFG'],
            [
                'name' => 'Brio RS',
                'brand' => 'Honda',
                'year' => 2021,
                'price_per_day' => 300000,
                'image_path' => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&q=80&w=600',
            ]
        );

        \App\Models\Car::updateOrCreate(
            ['license_plate' => 'B 9999 VIP'],
            [
                'name' => 'Alphard',
                'brand' => 'Toyota',
                'year' => 2023,
                'price_per_day' => 1500000,
                'image_path' => 'https://images.unsplash.com/photo-1503376760384-59e81fdd21d7?auto=format&fit=crop&q=80&w=600',
            ]
        );
    }
}
