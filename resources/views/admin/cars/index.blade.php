@extends('layouts.admin')

@push('admin_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/cars.css') }}">
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title" style="margin: 0;">Daftar Armada Mobil</h1>
    <a href="{{ route('admin.cars.create') }}" class="btn btn-primary" style="text-decoration: none;">+ Tambah Mobil Baru</a>
</div>

<div class="box table-box">
    <table class="data-table">
        <thead>
            <tr>
                <th>Foto</th>
                <th>Mobil & Merek</th>
                <th>Plat & Tahun</th>
                <th>Harga Sewa / Hari</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cars as $car)
            <tr>
                <td>
                    @if($car->image_path)
                        <img src="{{ $car->image_path }}" alt="Foto Mobil" class="img-thumbnail">
                    @else
                        <div class="no-img">No Img</div>
                    @endif
                </td>
                <td>
                    <div class="car-name">{{ $car->name }}</div>
                    <div class="car-brand">{{ $car->brand }}</div>
                </td>
                <td>
                    <div class="plate-badge">{{ $car->license_plate }}</div>
                    <div class="car-brand">Tahun: {{ $car->year }}</div>
                </td>
                <td>
                    <span class="price-text">Rp {{ number_format($car->price_per_day, 0, ',', '.') }}</span>
                </td>
                <td>
                    @if($car->is_available)
                        <span class="status-badge status-available">Tersedia</span>
                    @else
                        <span class="status-badge status-rented">Disewa</span>
                    @endif
                </td>
                <td>
                    <div class="action-links">
                        <a href="{{ route('admin.cars.show', $car->id) }}" class="link-detail">Detail</a>
                        <span class="action-separator">|</span>
                        <a href="{{ route('admin.cars.edit', $car->id) }}" class="link-edit">Edit</a>
                        <span class="action-separator">|</span>
                        <form action="{{ route('admin.cars.destroy', $car->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mobil ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="empty-state">
                    🚗 Belum ada koleksi mobil yang ditambahkan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
