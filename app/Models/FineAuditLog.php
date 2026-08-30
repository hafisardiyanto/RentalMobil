<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FineAuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_fine_id',
        'booking_id',
        'user_id',
        'action',
        'details',
        'old_amount',
        'new_amount'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function bookingFine()
    {
        return $this->belongsTo(BookingFine::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
