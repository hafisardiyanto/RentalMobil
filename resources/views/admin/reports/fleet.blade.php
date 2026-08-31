@extends('layouts.admin')

@push('admin_styles')
    <style>
        .kpi-board {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .kpi-card {
            background: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
            border: 1px solid #f1f5f9;
            text-align: center;
        }

        .kpi-val {
            font-size: 1.75rem;
            font-weight: 700;
            color: #0f172a;
            margin-top: 0.5rem;
        }

        .kpi-sub {
            font-size: 0.85rem;
            color: #64748b;
        }

        .util-bar-bg {
            width: 100%;
            background: #e2e8f0;
            height: 8px;
            border-radius: 4px;
            margin-top: 8px;
            overflow: hidden;
        }

        .util-bar-fill {
            height: 100%;
            border-radius: 4px;
        }

        .text-green {
            color: #10b981;
        }

        .text-red {
            color: #ef4444;
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <h1 class="page-title m-0">Kendali Utilisasi & ROI Armada (BETA)</h1>
    </div>

    <form class="filter-bar" method="GET" action="{{ route('admin.reports.fleet') }}"
        style="display:flex; gap:1rem; margin-bottom:2rem; align-items:flex-end;">
        <div class="form-group" style="margin:0;">
            <label class="form-label">Mulai Tanggal</label>
            <input type="date" name="start_date" class="form-control" value="{{ $startDate }}" style="width:150px;">
        </div>
        <div class="form-group" style="margin:0;">
            <label class="form-label">Sampai Tanggal</label>
            <input type="date" name="end_date" class="form-control" value="{{ $endDate }}" style="width:150px;">
        </div>
        <button type="submit" class="btn btn-primary">Terapkan Filter Range</button>
    </form>

    <div class="kpi-board">
        <div class="kpi-card">
            <div class="kpi-sub">Total Pendapatan ({{ $daysInPeriod }} Hari)</div>
            <div class="kpi-val text-green">Rp {{ number_format($totalFleetRevenue, 0, ',', '.') }}</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-sub">Total Biaya Perbaikan / Servis</div>
            <div class="kpi-val text-red">Rp {{ number_format($totalFleetMaintenance, 0, ',', '.') }}</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-sub">Laba Kotor Khusus Persewaan</div>
            <div class="kpi-val">Rp {{ number_format($totalFleetRevenue - $totalFleetMaintenance, 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="box table-box">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Peringkat</th>
                    <th>Mobil & Plat</th>
                    <th>Tingkat Pemakaian (Utilisasi)</th>
                    <th>Hari Sewa</th>
                    <th>Pendapatan Sewa</th>
                    <th>Biaya Servis</th>
                    <th>Net Profit (ROI)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fleetStats as $index => $stat)
                    <tr>
                        <td style="font-weight:bold; font-size:1.1rem; color:#64748b;">#{{ $index + 1 }}</td>
                        <td>
                            <b>{{ $stat['car']->name }}</b><br>
                            <small>{{ $stat['car']->license_plate }}</small>
                        </td>
                        <td>
                            <div>{{ number_format($stat['utilization_rate'], 1) }}% dari {{ $daysInPeriod }} hari</div>
                            <div class="util-bar-bg">
                                @php
                                    $barColor = $stat['utilization_rate'] > 75 ? '#10b981' : ($stat['utilization_rate'] > 40 ? '#f59e0b' : '#ef4444');
                                @endphp
                                <div class="util-bar-fill"
                                    style="width:{{ $stat['utilization_rate'] }}%; background:{{ $barColor }};"></div>
                            </div>
                        </td>
                        <td>{{ $stat['days_active'] }} Hari Aktif</td>
                        <td class="text-green">Rp {{ number_format($stat['revenue'], 0, ',', '.') }}</td>
                        <td class="text-red">Rp {{ number_format($stat['maintenance_cost'], 0, ',', '.') }}</td>
                        <td>
                            @if($stat['net_profit'] >= 0)
                                <strong style="color: #059669;">Rp {{ number_format($stat['net_profit'], 0, ',', '.') }}</strong>
                            @else
                                <strong style="color: #dc2626;">-Rp
                                    {{ number_format(abs($stat['net_profit']), 0, ',', '.') }}</strong>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top:20px; font-size:14px; color:#64748b;">
        <p>💡 <b>Cara Baca:</b> Tingkat Utilisasi 100% berarti mobil tersebut tidak pernah parkir nganggur di garasi anda
            selama rentang hari yang dipilih.</p>
    </div>
@endsection