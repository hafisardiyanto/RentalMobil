<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Booking;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', date('n'));
        $year = $request->get('year', date('Y'));

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfDay();
        $daysInMonth = $startDate->daysInMonth;
        $endDate = $startDate->copy()->endOfMonth();

        // Get cars with their bookings overlapping current month
        $cars = Car::with([
            'bookings' => function ($query) use ($startDate, $endDate) {
                $query->whereIn('status_booking', [
                    'Menunggu Konfirmasi',
                    'Menunggu Pembayaran',
                    'Pembayaran Diverifikasi',
                    'Booking Dikonfirmasi',
                    'Sedang Disewa',
                    'Menunggu Pengembalian'
                ])->where(function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('start_date', [$startDate, $endDate])
                        ->orWhereBetween('end_date', [$startDate, $endDate])
                        ->orWhere(function ($subq) use ($startDate, $endDate) {
                            $subq->where('start_date', '<', $startDate)->where('end_date', '>', $endDate);
                        });
                });
            }
        ])->get();

        return view('admin.calendar.index', compact('cars', 'startDate', 'endDate', 'daysInMonth', 'month', 'year'));
    }
}
