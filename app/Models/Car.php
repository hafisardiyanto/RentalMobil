<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'brand', 'license_plate', 'year', 'price_per_day', 'image_path', 'images', 'is_available'
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
