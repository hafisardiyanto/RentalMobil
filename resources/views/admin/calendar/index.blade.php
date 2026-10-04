@extends('layouts.admin')

@section('content')
    @push('admin_styles')
        <link rel="stylesheet" href="{{ asset('css/admin/calendar.css') }}">
    @endpush

    <div class="cal-header-flex">
        <h2 class="cal-header-title">📅 Kalender Availability (Gantt)</h2>
        <div class="month-nav">
            @php
                $prevMonth = $month - 1 == 0 ? 12 : $month - 1;
                $prevYear = $month - 1 == 0 ? $year - 1 : $year;
                $nextMonth = $month + 1 == 13 ? 1 : $month + 1;
                $nextYear = $month + 1 == 13 ? $year + 1 : $year;
                $monthName = \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y');
            @endphp

            <form action="{{ route('admin.calendar.index') }}">
                <input type="hidden" name="month" value="{{ $prevMonth }}">
                <input type="hidden" name="year" value="{{ $prevYear }}">
                <button type="submit" class="btn btn-sm btn-outline-primary">&laquo; Mundur</button>
            </form>

            <span style="font-weight:bold; margin:0 15px; font-size:1.1rem; color:#0f172a;">{{ $monthName }}</span>

            <form action="{{ route('admin.calendar.index') }}">
                <input type="hidden" name="month" value="{{ $nextMonth }}">
                <input type="hidden" name="year" value="{{ $nextYear }}">
                <button type="submit" class="btn btn-sm btn-outline-primary">Maju &raquo;</button>
            </form>
        </div>
    </div>

    <div class="cal-container">
        <div style="margin-bottom: 10px;">
            <span class="badge" style="background: #3b82f6; padding: 5px 10px; color:#fff;">🟦 Disewa (Booking)</span>
            <span class="badge" style="background: #f59e0b; padding: 5px 10px; color:#fff;">🟧 Bengkel (Maintenance)</span>
        </div>
        <div style="overflow-x:auto;">
            <table class="cal-table">
                <thead>
                    <tr>
                        <th class="car-col">Nama Armada Mobil</th>
                        @for($d = 1; $d <= $daysInMonth; $d++)
                            <th>{{ $d }}</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @foreach($cars as $car)
                        <tr>
                            <td class="car-col">{{ $car->brand }} {{ $car->name }} <br><small
                                    style="color:#64748b;">{{ $car->license_plate }}</small></td>

                            @for($d = 1; $d <= $daysInMonth; $d++)
                                @php
                                    $currentDate = \Carbon\Carbon::createFromDate($year, $month, $d)->format('Y-m-d');
                                    $isBooked = false;
                                    $isMaintenance = false;
                                    $bookTitle = "";
                                    $maintTitle = "";
                                    $cellClass = "";

                                    foreach ($car->bookings as $b) {
                                        $bStart = substr($b->start_date, 0, 10);
                                        $bEnd = substr($b->end_date, 0, 10);
                                        if ($currentDate >= $bStart && $currentDate <= $bEnd) {
                                            $isBooked = true;
                                            $bookTitle = $b->nomor_booking . " - " . $b->user->name;
                                            break;
                                        }
                                    }

                                    foreach ($car->maintenances as $m) {
                                        $mDate = substr($m->service_date, 0, 10);
                                        if ($currentDate == $mDate) { // maintenance usually lasts 1 day in current logic
                                            $isMaintenance = true;
                                            $maintTitle = $m->type;
                                            break;
                                        }
                                    }

                                    if ($isMaintenance) {
                                        $cellClass = 'bg-maintenance';
                                    } elseif ($isBooked) {
                                        $cellClass = 'bg-booking';
                                    }
                                @endphp

                                <td class="{{ $cellClass }}"
                                    title="{{ $isMaintenance ? $maintTitle : ($isBooked ? $bookTitle : 'Kosong') }}">
                                    @if($isMaintenance && $d == substr($m->service_date, 8, 2))
                                        🔧
                                    @elseif($isBooked && $d == substr($bStart, 8, 2))
                                        &rarr;
                                    @endif
                                </td>
                            @endfor
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection