<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'type',
        'amount',
        'payment_method',
        'payment_proof',
        'status',
        'verified_by',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
