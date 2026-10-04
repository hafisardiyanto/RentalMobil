<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Faker\Factory as Faker;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Opsional: Buat 1 customer paten untuk Anda uji coba login gampang
        User::updateOrCreate(
            ['email' => 'customer@test.com'],
            [
                'name' => 'Budi Customer',
                'phone' => '081234567890',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'nik' => '3201011234567890',
                'address' => 'Jl. Merdeka No. 10, Jakarta Selatan',
            ]
        );

        // Buat 15 Customer Acak Tambahan
        for ($i = 0; $i < 15; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'password' => Hash::make('password'),
                'role' => 'user',
                'nik' => $faker->numerify('################'), // 16 digit NIK
                'address' => $faker->address,
            ]);
        }
    }
}
