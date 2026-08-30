<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingFine extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'type',
        'part_name',
        'description',
        'amount',
        'photo_path',
        'created_by'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function auditLogs()
    {
        return $this->hasMany(FineAuditLog::class);
    }
}
