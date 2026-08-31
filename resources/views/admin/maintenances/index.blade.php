@extends('layouts.admin')

@push('admin_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/cars.css') }}">
@endpush

@section('content')
    <div class="page-header">
        <h1 class="page-title m-0">Jadwal Perawatan & Servis</h1>
    </div>

    @if(count($alerts) > 0)
        <div class="alert alert-danger"
            style="background:#fef2f2; border: 1px solid #fecaca; color: #991b1b; padding:1rem; border-radius:8px; margin-bottom: 2rem;">
            <h4 style="margin-top:0">⚠️ Peringatan Mobil Dalam Servis</h4>
            <ul style="margin-bottom:0">
                @foreach($alerts as $alertCar)
                    <li>{{ $alertCar->name }} ({{ $alertCar->license_plate }}) saat ini berstatus <strong>Maintenance/Sedang
                            Diservis</strong> dan tidak dapat disewa.</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="box table-box">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Mobil & Plat</th>
                    <th>Tgl Servis</th>
                    <th>Jenis Perawatan</th>
                    <th>Biaya</th>
                    <th>KM Terakhir</th>
                    <th>Next KM</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($maintenances as $maintenance)
                    <tr>
                        <td>
                            <div class="car-name">{{ $maintenance->car->name }}</div>
                            <div class="plate-badge">{{ $maintenance->car->license_plate }}</div>
                        </td>
                        <td>{{ date('d M Y', strtotime($maintenance->service_date)) }}</td>
                        <td>
                            <b>{{ $maintenance->type }}</b><br>
                            <small>{{ $maintenance->description }}</small>
                        </td>
                        <td><span class="price-text">Rp {{ number_format($maintenance->cost, 0, ',', '.') }}</span></td>
                        <td>{{ number_format((float) $maintenance->last_km, 0, ',', '.') }} KM</td>
                        <td>{{ number_format((float) $maintenance->next_km, 0, ',', '.') }} KM</td>
                        <td>
                            @php
                                $bgClass = match ($maintenance->status) {
                                    'Dalam Proses' => 'bg-pending',
                                    'Selesai' => 'bg-rented',
                                    default => 'bg-available'
                                };
                            @endphp
                            <span class="status-badge {{ $bgClass }}">
                                {{ $maintenance->status }}
                            </span>
                        </td>
                        <td>
                            <div class="action-links">
                                @if($maintenance->status === 'Dalam Proses')
                                    <form action="{{ route('admin.maintenances.update', $maintenance->id) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Tandai servis selesai dan aktifkan mobil kembali?')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-success"
                                            style="background:#10b981; color:white; border:none; padding:4px 8px; border-radius:4px; font-weight:600; cursor:pointer;">Selesai</button>
                                    </form>
                                @endif

                                @can('delete_cars')
                                    <span class="action-separator">|</span>
                                    <form action="{{ route('admin.maintenances.destroy', $maintenance->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Hapus riwayat perawatan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete"
                                            style="background:none; border:none; color:#ef4444; cursor:pointer; font-weight:500;">Hapus</button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="empty-state">
                            Belum ada riwayat perawatan atau servis mobil.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection