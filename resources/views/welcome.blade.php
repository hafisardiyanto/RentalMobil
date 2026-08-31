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
                @if(is_object($car))
                    <div class="card">
                        <img src="{{ $car->image_path ?? 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&q=80&w=600' }}"
                            alt="{{ $car->name }}">
                        <h3>{{ $car->brand }} {{ $car->name }}</h3>
                        <p class="car-meta-text">Tahun {{ $car->year }} &bull; Plat: {{ $car->license_plate }}</p>
                        <div class="price">Rp {{ number_format($car->price_per_day, 0, ',', '.') }}<span
                                class="price-suffix">/hari</span></div>

                        <div style="display: flex; flex-direction: column; gap: 8px; margin-top: 15px;">
                            @php
                                $waText = urlencode("Halo, saya ingin menanyakan mobil {$car->brand} {$car->name}. Apakah masih tersedia untuk disewa?");
                            @endphp
                            <a href="https://wa.me/6285748174062?text={{ $waText }}" target="_blank"
                                class="btn btn-outline btn-full-width"
                                style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="#25D366" stroke="#25D366" stroke-width="0">
                                    <path
                                        d="M12.01 2.01c-5.46 0-9.89 4.43-9.89 9.89 0 1.74.45 3.4 1.3 4.88L2 22l5.44-1.42c1.44.78 3.06 1.22 4.57 1.22 5.46 0 9.89-4.43 9.89-9.89 0-5.46-4.43-9.89-9.89-9.89zM12.01 19.98c-1.47 0-2.91-.39-4.18-1.14l-.3-.18-3.1.81.82-3.03-.2-.31c-.82-1.28-1.25-2.76-1.25-4.28 0-4.46 3.63-8.08 8.08-8.08 4.46 0 8.08 3.63 8.08 8.08s-3.63 8.08-8.08 8.08zm4.43-6.07c-.24-.12-1.44-.71-1.66-.79-.22-.08-.38-.12-.54.12-.16.24-.62.79-.76.95-.14.16-.28.18-.52.06-1.25-.63-2.39-1.42-3.19-2.35-.22-.26.01-.24.23-.68.08-.16.04-.3-.02-.42-.06-.12-.54-1.3-.74-1.78-.19-.47-.38-.41-.54-.41-.16 0-.34-.02-.5-.02s-.42.06-.64.3c-.22.24-.84.82-.84 2.01 0 1.19.86 2.34.98 2.5.12.16 1.7 2.61 4.14 3.65.58.25 1.03.4 1.38.51.58.19 1.12.16 1.54.1.47-.07 1.44-.59 1.64-1.16.2-.57.2-1.06.14-1.16-.06-.1-.22-.16-.46-.28z" />
                                </svg>
                                Tanya via WhatsApp
                            </a>
                            <a href="{{ route('bookings.create', $car->id) }}" class="btn btn-primary btn-full-width">🚗 Sewa
                                Sekarang</a>
                        </div>
                    </div>
                @endif
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

    <div class="features" id="faq" style="background-color: #fff;">
        <h2>Pertanyaan Umum & Syarat Rental (FAQ)</h2>
        <div style="max-width: 800px; margin: 0 auto; text-align: left;">
            <div style="margin-bottom: 20px; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px;">
                <h4 style="margin-top: 0; color: #1e293b;">Apakah harus memiliki SIM?</h4>
                <p style="color: #64748b; margin-bottom: 0;">Ya, Customer wajib memiliki SIM A yang masih berlaku dan KTP
                    asli saat proses serah terima kendaraan.</p>
            </div>
            <div style="margin-bottom: 20px; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px;">
                <h4 style="margin-top: 0; color: #1e293b;">Apakah ada deposit per unit mobil?</h4>
                <p style="color: #64748b; margin-bottom: 0;">Ya, deposit diwajibkan sebagai jaminan di mana jumlahnya
                    bervariasi tergantung mobil (Contoh minimal Rp500.000). Dana deposit akan dikembalikan 100% saat mobil
                    kembali dalam kondisi baik.</p>
            </div>
            <div style="margin-bottom: 20px; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px;">
                <h4 style="margin-top: 0; color: #1e293b;">Apakah boleh terlambat mengembalikan mobil?</h4>
                <p style="color: #64748b; margin-bottom: 0;">Bisa (toleransi dibicarakan), tetapi jika melebihi batas jadwal
                    (contoh > 2 jam), maka Anda akan dikenakan sistem **Denda Keterlambatan** harian sesuai ketetapan
                    operasional.</p>
            </div>
            <div style="margin-bottom: 20px; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px;">
                <h4 style="margin-top: 0; color: #1e293b;">Bagaimana pertanggungan jika terjadi kerusakan/baret?</h4>
                <p style="color: #64748b; margin-bottom: 0;">Segala kerusakan yang terjadi selama masa periode sewa di dalam
                    kontrak sepenuhnya menjadi tanggung jawab Customer sesuai standar pemeriksaan saat proses pengembalian
                    (Return).</p>
            </div>
        </div>
    </div>

@endsection