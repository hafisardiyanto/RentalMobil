@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')

<div class="hero">
    <div class="hero-text">
        <h1>Perjalanan Mewah <br>Mulai dari Sini.</h1>
        <p>Sewa mobil eksklusif dengan mudah, aman, dan harga terbaik. Nikmati pengalaman berkendara kelas satu untuk setiap momen berharga Anda.</p>
        <a href="#armada" class="btn btn-primary" style="font-size: 1.1rem; padding: 1rem 2rem;">Lihat Armada Kami</a>
    </div>
    <div class="hero-image">
        <!-- Placeholder for a premium car image -->
        <div style="background: url('https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&q=80&w=1000') no-repeat center center / cover; width: 100%; max-width: 600px; height: 400px; border-radius: 20px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); display: inline-block;"></div>
    </div>
</div>

<div class="features" id="armada">
    <h2>Armada Unggulan Kami</h2>
    <div class="grid">
        @forelse($featuredCars ?? [] as $car)
        <div class="card">
            <img src="{{ $car->image_path ?? 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&q=80&w=600' }}" alt="{{ $car->name }}">
            <h3>{{ $car->brand }} {{ $car->name }}</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Tahun {{ $car->year }} &bull; Plat: {{ $car->license_plate }}</p>
            <div class="price">Rp {{ number_format($car->price_per_day, 0, ',', '.') }}<span style="font-size: 0.9rem; color: var(--text-muted); font-weight: 400;">/hari</span></div>
            <a href="{{ route('bookings.create', $car->id) }}" class="btn btn-primary" style="width: 100%; text-align: center;">Sewa Sekarang</a>
        </div>
        @empty
        <div class="card" style="grid-column: 1 / -1; text-align: center; background: #FFFBEB; border: 1px solid #FCD34D;">
            <p style="color: #B45309; padding: 2rem;">Belum ada mobil yang ditambahkan di database. Gunakan seeder untuk menambah data.</p>
        </div>
        @endforelse
    </div>
</div>

<div class="features" id="tentang" style="background: var(--background);">
    <h2>Mengapa Memilih Kami?</h2>
    <div class="grid">
        <div class="card" style="text-align: center;">
            <div style="font-size: 3rem; color: var(--primary); margin-bottom: 1rem;">🛡️</div>
            <h3>Aman & Terpercaya</h3>
            <p style="color: var(--text-muted);">Kendaraan selalu dicek secara berkala untuk memastikan keamanan Anda di jalan.</p>
        </div>
        <div class="card" style="text-align: center;">
            <div style="font-size: 3rem; color: var(--primary); margin-bottom: 1rem;">💎</div>
            <h3>Kondisi Premium</h3>
            <p style="color: var(--text-muted);">Kebersihan dan kenyamanan mobil layaknya mobil baru untuk pengalaman terbaik.</p>
        </div>
        <div class="card" style="text-align: center;">
            <div style="font-size: 3rem; color: var(--primary); margin-bottom: 1rem;">⏱️</div>
            <h3>Dukungan 24/7</h3>
            <p style="color: var(--text-muted);">Tim kami siap membantu Anda kapan saja terjadi kendala di jalan.</p>
        </div>
    </div>
</div>

@endsection
