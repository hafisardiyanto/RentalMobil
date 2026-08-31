<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;

class HomeController extends Controller
{
    public function index()
    {
        // Get 6 featured cars available
        $featuredCars = Car::where('is_available', true)->take(6)->get();
        return view('welcome', compact('featuredCars'));
    }

    public function armada(Request $request)
    {
        $query = Car::query();

        // If user submitted quick search from homepage
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;

            // Find cars that DO NOT have overlapping accepted bookings
            $query->whereDoesntHave('bookings', function ($q) use ($startDate, $endDate) {
                $q->whereNotIn('status_booking', ['Dibatalkan', 'Ditolak'])
                    ->where(function ($subq) use ($startDate, $endDate) {
                        $subq->whereBetween('start_date', [$startDate, $endDate])
                            ->orWhereBetween('end_date', [$startDate, $endDate])
                            ->orWhere(function ($subq2) use ($startDate, $endDate) {
                                $subq2->where('start_date', '<=', $startDate)
                                    ->where('end_date', '>=', $endDate);
                            });
                    });
            });
            // also exclude cars in maintenance
            $query->whereDoesntHave('maintenances', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('date', [$startDate, $endDate]);
            });
        }

        $cars = $query->orderBy('is_available', 'desc')->paginate(12);

        return view('armada', compact('cars'));
    }
}
