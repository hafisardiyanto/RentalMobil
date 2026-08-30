@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')

    <div class="hero">
        <div class="hero-text">
            <h1>Perjalanan Mewah <br>Mulai dari Sini.</h1>
            <p>Sewa mobil eksklusif dengan mudah, aman, dan harga terbaik. Nikmati pengalaman berkendara kelas satu untuk
                setiap momen berharga Anda.</p>
            <a href="#armada" class="btn btn-primary hero-primary-btn">Lihat Armada Kami</a>
        </div>
        <div class="hero-image">
            <div class="hero-placeholder-img"></div>
        </div>
    </div>

    <div class="features" id="armada">
        <h2>Armada Unggulan Kami</h2>
        <div class="grid">
            @forelse($featuredCars ?? [] as $car)
                <div class="card">
                    <img src="{{ $car->image_path ?? 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&q=80&w=600' }}"
                        alt="{{ $car->name }}">
                    <h3>{{ $car->brand }} {{ $car->name }}</h3>
                    <p class="car-meta-text">Tahun {{ $car->year }} &bull; Plat: {{ $car->license_plate }}</p>
                    <div class="price">Rp {{ number_format($car->price_per_day, 0, ',', '.') }}<span
                            class="price-suffix">/hari</span></div>
                    <a href="{{ route('bookings.create', $car->id) }}" class="btn btn-primary btn-full-width">Sewa Sekarang</a>
                </div>
            @empty
                <div class="card empty-car-card">
                    <p class="empty-car-text">Belum ada mobil yang ditambahkan di database. Gunakan seeder untuk menambah data.
                    </p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="features features-bg" id="tentang">
        <h2>Mengapa Memilih Kami?</h2>
        <div class="grid">
            <div class="card feature-card">
                <div class="feature-icon">🛡️</div>
                <h3>Aman & Terpercaya</h3>
                <p class="feature-desc">Kendaraan selalu dicek secara berkala untuk memastikan keamanan Anda di jalan.</p>
            </div>
            <div class="card feature-card">
                <div class="feature-icon">💎</div>
                <h3>Kondisi Premium</h3>
                <p class="feature-desc">Kebersihan dan kenyamanan mobil layaknya mobil baru untuk pengalaman terbaik.</p>
            </div>
            <div class="card feature-card">
                <div class="feature-icon">⏱️</div>
                <h3>Dukungan 24/7</h3>
                <p class="feature-desc">Tim kami siap membantu Anda kapan saja terjadi kendala di jalan.</p>
            </div>
        </div>
    </div>

@endsection