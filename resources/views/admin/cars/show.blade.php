@extends('layouts.admin')

@push('admin_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/cars.css') }}">
@endpush

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title m-0">Detail Mobil</h1>
            <p>Informasi lengkap untuk armada: {{ $car->name }}</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.cars.edit', $car->id) }}" class="btn btn-primary text-decoration-none">Edit Mobil</a>
            <a href="{{ route('admin.cars.index') }}" class="btn btn-cancel">Kembali</a>
        </div>
    </div>

    <div class="box form-container mb-2">
        <h3 class="section-title">Informasi Utama</h3>
        <div class="detail-grid">
            <div>
                <span class="detail-label">Nama Tipe Mobil</span>
                <div class="detail-value">{{ $car->name }}</div>
            </div>
            <div>
                <span class="detail-label">Merek (Brand)</span>
                <div class="detail-value">{{ $car->brand }}</div>
            </div>
            <div>
                <span class="detail-label">Plat Nomor</span>
                <div>
                    <div class="detail-plate">{{ $car->license_plate }}</div>
                </div>
            </div>
            <div>
                <span class="detail-label">Tahun Keluar</span>
                <div class="detail-value">{{ $car->year }}</div>
            </div>
            <div>
                <span class="detail-label">Harga Sewa per Hari</span>
                <div class="detail-price">Rp {{ number_format($car->price_per_day, 0, ',', '.') }}</div>
            </div>
            <div>
                <span class="detail-label">Status Ketersediaan</span>
                <div class="detail-status">
                    @if($car->is_available)
                        <span class="status-available">Tersedia</span>
                    @else
                        <span class="status-rented">Booking / Disewa</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="box">
        <h3 class="section-title">Galeri Foto Kendaraan</h3>
        <div class="gallery-grid">
            @if(is_array($car->images) && count($car->images) > 0)
                @foreach($car->images as $index => $img)
                    <div class="gallery-item" style="position: relative;">
                        <button type="button"
                            onclick="if(confirm('Hapus foto ini dari galeri?')) document.getElementById('delete-img-show-{{ $index }}').submit();"
                            style="position: absolute; top:5px; right:5px; background:#ef4444; color:white; border:none; border-radius:50%; width:25px; height:25px; cursor:pointer; font-weight:bold; box-shadow: 0 2px 4px rgba(0,0,0,0.2); z-index: 10;">&times;</button>
                        <img src="{{ $img }}" alt="Foto {{ $index + 1 }}">
                        <div class="gallery-item-label">Foto {{ $index + 1 }}</div>
                        <form id="delete-img-show-{{ $index }}" action="{{ route('admin.cars.destroy-image', $car->id) }}"
                            method="POST" style="display:none;">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="image_url" value="{{ $img }}">
                        </form>
                    </div>
                @endforeach
            @elseif($car->image_path)
                <div class="gallery-item">
                    <img src="{{ $car->image_path }}" alt="Foto Utama">
                    <div class="gallery-item-label">Foto 1</div>
                </div>
            @else
                <div class="gallery-empty">
                    <span class="gallery-empty-icon">📷</span>
                    <span class="gallery-empty-text">Belum ada foto kendaraan yang diupload.</span>
                </div>
            @endif
        </div>
    </div>
@endsection