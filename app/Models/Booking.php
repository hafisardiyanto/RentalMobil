<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_booking',
        'user_id',
        'car_id',
        'start_date',
        'end_date',
        'durasi',
        'harga_per_hari',
        'subtotal',
        'diskon',
        'total',
        'deposit',
        'tagihan_susulan',
        'status_booking',
        'status_pembayaran',
        'catatan',
        'payment_proof',
        'km_awal',
        'bbm_awal',
        'kondisi_awal',
        'foto_awal',
        'km_akhir',
        'bbm_akhir',
        'kondisi_akhir',
        'foto_akhir',
        'waktu_pengembalian',
        'denda_terlambat',
        'biaya_kerusakan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function fines()
    {
        return $this->hasMany(BookingFine::class);
    }

    public function fineAuditLogs()
    {
        return $this->hasMany(FineAuditLog::class);
    }
}
