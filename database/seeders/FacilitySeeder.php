<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = [
            'Lepas Kunci',
            'Termasuk Pengemudi',
            'Mobil + Driver + BBM',
            'Durasi 12 Jam',
            'Unit Bersih dan Terawat'
        ];

        foreach ($facilities as $facility) {
            \App\Models\Facility::firstOrCreate(['name' => $facility]);
        }
    }
}
