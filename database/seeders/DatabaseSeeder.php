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
        // Seeding Akun Admin (Operasional)
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@rentalmobil.com'],
            [
                'name' => 'Administrator Rental',
                'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
                'role' => 'admin'
            ]
        );

        // Seeding Akun Owner (Pemilik Bisnis)
        \App\Models\User::updateOrCreate(
            ['email' => 'owner@rentalmobil.com'],
            [
                'name' => 'Pemilik Bisnis Utama',
                'password' => \Illuminate\Support\Facades\Hash::make('owner123'),
                'role' => 'owner'
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
                'image_path' => '/storage/cars/inova reborn.jpg',
                'images' => ['/storage/cars/inova reborn.jpg'],
            ]
        );

        \App\Models\Car::updateOrCreate(
            ['license_plate' => 'D 5678 EFG'],
            [
                'name' => 'Brio RS',
                'brand' => 'Honda',
                'year' => 2021,
                'price_per_day' => 300000,
                'image_path' => '/storage/cars/inova reborn.jpg',
                'images' => ['/storage/cars/inova reborn.jpg'],
            ]
        );

        \App\Models\Car::updateOrCreate(
            ['license_plate' => 'B 9999 VIP'],
            [
                'name' => 'Alphard',
                'brand' => 'Toyota',
                'year' => 2023,
                'price_per_day' => 1500000,
                'image_path' => '/storage/cars/inova reborn.jpg',
                'images' => ['/storage/cars/inova reborn.jpg'],
            ]
        );
    }
}
