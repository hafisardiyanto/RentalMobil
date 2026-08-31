<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand',
        'license_plate',
        'year',
        'price_per_day',
        'image_path',
        'images',
        'is_available',
        'status_mobil',
        'description',
        'seats',
        'luggage',
        'facilities'
    ];

    protected $casts = [
        'images' => 'array',
        'facilities' => 'array',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

    /**
     * Mengecek ketersediaan mobil berdasarkan retang tanggal, 
     * mengabaikan booking yang statusnya ditolak/dibatalkan/selesai.
     */
    public function isAvailableBetween($startDate, $endDate)
    {
        if (!$this->is_available) {
            return false;
        }

        // Cek irisan booking
        $overlappingBookings = $this->bookings()
            ->whereIn('status_booking', [
                'Menunggu Konfirmasi',
                'Menunggu Pembayaran',
                'Pembayaran Diverifikasi',
                'Booking Dikonfirmasi',
                'Sedang Disewa',
                'Menunggu Pengembalian'
            ])
            ->where(function ($query) use ($startDate, $endDate) {
                // Saling bersinggungan tanggal
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                    $q->where('start_date', '<=', $startDate)
                        ->where('end_date', '>=', $endDate);
                });
            })
            ->count();

        return $overlappingBookings === 0;
    }
}
