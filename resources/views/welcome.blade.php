@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <style>
        .category-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            text-align: center;
            border: 1px solid #e2e8f0;
            transition: transform 0.3s;
        }

        .category-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary);
        }

        .category-icon {
            font-size: 3rem;
            margin-bottom: 10px;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            height: 200px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .gallery-item:hover img {
            transform: scale(1.1);
        }

        .gallery-caption {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
            color: white;
            padding: 20px 10px 10px 10px;
            font-weight: bold;
            text-align: center;
            font-size: 1.1rem;
        }

        .pricing-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border-style: hidden;
        }

        .pricing-table th,
        .pricing-table td {
            padding: 15px 20px;
            border: 1px solid #e2e8f0;
            text-align: left;
        }

        .pricing-table th {
            background: #f8fafc;
            color: #1e293b;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .area-list {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
            margin-top: 20px;
        }

        .area-badge {
            background: #eff6ff;
            color: #1d4ed8;
            padding: 12px 25px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            border: 1px solid #bfdbfe;
            transition: all 0.3s;
        }

        .area-badge:hover {
            background: #dbeafe;
            transform: scale(1.05);
        }

        .testimonial-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            text-align: left;
            border: 1px solid #e2e8f0;
        }

        .stars {
            color: #fbbf24;
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .cta-section {
            background: linear-gradient(135deg, var(--primary) 0%, #1e3a8a 100%);
            color: white;
            padding: 4rem 2rem;
            border-radius: 16px;
            text-align: center;
            margin: 4rem auto;
            max-width: 1000px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .cta-section h2 {
            color: white;
            margin-bottom: 15px;
            font-size: 2.2rem;
        }

        .cta-section p {
            font-size: 1.1rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        .cta-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .step-box {
            flex: 1 1 250px;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            position: relative;
            text-align: left;
            border: 1px solid #e2e8f0;
        }

        .step-number {
            color: #f1f5f9;
            font-weight: 800;
            font-size: 3.5rem;
            position: absolute;
            top: -5px;
            right: 20px;
            line-height: 1;
            z-index: 0;
        }

        .step-content {
            position: relative;
            z-index: 1;
        }

        .security-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            max-width: 800px;
            margin: 0 auto;
            text-align: left;
        }

        .security-item {
            display: flex;
            align-items: center;
            gap: 15px;
            background: white;
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            font-weight: 500;
            color: #334155;
        }

        .security-icon {
            font-size: 1.8rem;
        }
    </style>
@endpush

@section('content')

    <div class="hero">
        <div class="hero-text">
            <h1>Perjalanan Mewah <br>Mulai dari Sini.</h1>
            <p>Sewa mobil eksklusif dengan mudah, aman, dan harga terbaik. Nikmati pengalaman berkendara kelas satu untuk
                setiap momen berharga Anda.</p>
            <a href="#armada" class="btn btn-primary hero-primary-btn">Mulai Booking</a>
        </div>
        <div class="hero-image">
            <div class="hero-placeholder-img"></div>
        </div>
    </div>

    <!-- Quick Search Widget -->
    <div class="search-widget"
        style="max-width: 900px; margin: -50px auto 40px auto; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); position: relative; z-index: 10;">
        <h3 style="margin-top: 0; color: #1e293b; text-align: center; margin-bottom: 20px;">🔎 Cari Kendaraan yang Tersedia
        </h3>
        <form action="/armada" method="GET"
            style="display: flex; gap: 15px; flex-wrap: wrap; justify-content: center; align-items: flex-end;">
            <div style="flex: 1; min-width: 200px;">
                <label
                    style="display:block; font-size: 0.85rem; font-weight: bold; margin-bottom: 5px; color: #64748b;">Mulai
                    Sewa</label>
                <input type="date" name="start_date" required class="form-control-cs"
                    style="width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px;">
            </div>
            <div style="flex: 1; min-width: 200px;">
                <label
                    style="display:block; font-size: 0.85rem; font-weight: bold; margin-bottom: 5px; color: #64748b;">Selesai
                    Sewa</label>
                <input type="date" name="end_date" required class="form-control-cs"
                    style="width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px;">
            </div>
            <div style="flex: 1; min-width: 200px;">
                <button type="submit" class="btn btn-primary"
                    style="width: 100%; padding: 12px; border-radius: 8px; height: 43px;">Cari Mobil &rsaquo;</button>
            </div>
        </form>
    </div>

    <!-- ARMADA UNGGULAN -->
    <div class="features" id="armada">
        <h2>Armada Unggulan Kami</h2>
        <div class="grid">
            @forelse($featuredCars ?? [] as $car)
                @if(is_object($car))
                    <div class="card">
                        <!-- Status Badge -->
                        @if($car->is_available)
                            <div
                                style="position: absolute; top: 15px; right: 15px; background: rgba(255,255,255,0.9); padding: 5px 12px; border-radius: 20px; font-weight: bold; font-size: 0.8rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); display: flex; align-items: center; gap: 6px;">
                                <span
                                    style="display:inline-block; width:8px; height:8px; background: #10b981; border-radius: 50%;"></span>
                                Tersedia
                            </div>
                        @else
                            <div
                                style="position: absolute; top: 15px; right: 15px; background: rgba(255,255,255,0.9); padding: 5px 12px; border-radius: 20px; font-weight: bold; font-size: 0.8rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); display: flex; align-items: center; gap: 6px;">
                                <span
                                    style="display:inline-block; width:8px; height:8px; background: #ef4444; border-radius: 50%;"></span>
                                Sedang Dipakai
                            </div>
                        @endif

                        <img src="{{ $car->image_path ?: '/storage/cars/inova%20reborn.jpg' }}"
                            onerror="this.onerror=null; this.src='/storage/cars/inova%20reborn.jpg'" alt="{{ $car->name }}">
                        <h3>{{ $car->brand }} {{ $car->name }}</h3>
                        <p class="car-meta-text">Tahun {{ $car->year }} &bull; Plat: {{ $car->license_plate }}</p>

                        <div
                            style="display: flex; gap: 15px; margin-top: 5px; margin-bottom: 15px; font-size: 0.85rem; color: #475569;">
                            <span>👤 {{ $car->seats }} Kursi</span>
                            <span>🧳 {{ $car->luggage }} Koper</span>
                        </div>

                        <div class="price">Rp {{ number_format($car->price_per_day, 0, ',', '.') }}<span
                                class="price-suffix">/hari</span></div>

                        <div style="display: flex; flex-direction: column; gap: 8px; margin-top: 15px;">
                            <a href="{{ route('bookings.create', $car->id) }}" class="btn btn-primary btn-full-width">🚗 Sewa
                                Sekarang</a>
                        </div>
                    </div>
                @endif
            @empty
                <div class="card empty-car-card">
                    <p class="empty-car-text">Belum ada mobil yang ditambahkan di database.</p>
                </div>
            @endforelse
        </div>
        <div style="text-align: center; margin-top: 30px;">
            <a href="/armada" class="btn btn-outline" style="padding: 12px 30px; font-weight: bold;">Lihat Semua Armada
                &rsaquo;</a>
        </div>
    </div>

    <!-- MENGAPA MEMILIH KAMI -->
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
                <div class="feature-icon">📅</div>
                <h3>Booking Mudah</h3>
                <p class="feature-desc">Pesan kendaraan secara online dengan sistem pengecekan ketersediaan instan.</p>
            </div>
            <div class="card feature-card">
                <div class="feature-icon">💬</div>
                <h3>Dukungan 24/7</h3>
                <p class="feature-desc">Tim kami (Mekanik & CS) siap membantu Anda kapan saja terjadi kendala di jalan.</p>
            </div>
        </div>
    </div>

    <!-- KATEGORI ARMADA -->
    <div class="features" style="background-color: #fff;">
        <h2>Pilih Mobil Sesuai Kebutuhan</h2>
        <div class="grid">
            <div class="category-card">
                <div class="category-icon">🚗</div>
                <h3 style="color: #1e293b;">City Car</h3>
                <p style="color: #64748b; font-weight: bold;">Mulai Rp250.000/hari</p>
                <p style="font-size: 0.9rem; color: #94a3b8; margin-top: 5px;">Honda Brio, Agya, Ayla</p>
            </div>
            <div class="category-card">
                <div class="category-icon">🚙</div>
                <h3 style="color: #1e293b;">MPV</h3>
                <p style="color: #64748b; font-weight: bold;">Mulai Rp350.000/hari</p>
                <p style="font-size: 0.9rem; color: #94a3b8; margin-top: 5px;">Toyota Avanza, Xenia, Ertiga</p>
            </div>
            <div class="category-card">
                <div class="category-icon">💎</div>
                <h3 style="color: #1e293b;">Premium</h3>
                <p style="color: #64748b; font-weight: bold;">Mulai Rp500.000/hari</p>
                <p style="font-size: 0.9rem; color: #94a3b8; margin-top: 5px;">Toyota Innova Reborn, Zenix</p>
            </div>
            <div class="category-card">
                <div class="category-icon">🏔️</div>
                <h3 style="color: #1e293b;">SUV</h3>
                <p style="color: #64748b; font-weight: bold;">Mulai Rp800.000/hari</p>
                <p style="font-size: 0.9rem; color: #94a3b8; margin-top: 5px;">Toyota Fortuner, Pajero Sport</p>
            </div>
        </div>
    </div>

    <!-- KONDISI ARMADA -->
    <div class="features features-bg">
        <h2>Kondisi Armada Kami</h2>
        <p style="text-align:center; max-width: 600px; margin: -20px auto 30px auto; color: #64748b; font-size: 1.1rem;">
            Kami menjaga setiap kendaraan selalu dalam kondisi prima, bersih, wangi, dan siap melayani perjalanan Anda.</p>
        <div class="gallery-grid" style="max-width: 1000px; margin: 0 auto;">
            <div class="gallery-item">
                <img src="/storage/cars/inova reborn.jpg" alt="Eksterior">
                <div class="gallery-caption">Tampilan Eksterior Mulus</div>
            </div>
            <div class="gallery-item">
                <img src="/storage/cars/inova reborn.jpg" alt="Interior">
                <div class="gallery-caption">Kabin Bersih & Nyaman</div>
            </div>
            <div class="gallery-item">
                <img src="/storage/cars/inova reborn.jpg" alt="Bagasi">
                <div class="gallery-caption">Bagasi Luas & Aman</div>
            </div>
        </div>
    </div>

    <!-- KEAMANAN & KONDISI (OPERASIONAL) -->
    <div class="features" style="background-color: #fff;">
        <h2>Kendaraan Selalu Kami Persiapkan</h2>
        <div class="security-list">
            <div class="security-item"><span class="security-icon">🔧</span> Pemeriksaan Berkala</div>
            <div class="security-item"><span class="security-icon">🧼</span> Interior & Eksterior Dibersihkan</div>
            <div class="security-item"><span class="security-icon">🛞</span> Pemeriksaan Ban & Rem</div>
            <div class="security-item"><span class="security-icon">🛢️</span> Penggantian Cairan & Oli</div>
            <div class="security-item"><span class="security-icon">📄</span> Dokumen Legalitas Aktif</div>
            <div class="security-item"><span class="security-icon">🔑</span> Serah Terima Lancar</div>
        </div>
    </div>

    <!-- CARA SEWA -->
    <div class="features features-bg" id="cara-sewa">
        <h2>Alur Booking Fleksibel & Cepat</h2>
        <div style="display: flex; flex-wrap: wrap; gap: 20px; max-width: 1100px; margin: 0 auto; justify-content: center;">
            <div class="step-box">
                <div class="step-number">01</div>
                <div class="step-content">
                    <h3 style="margin-top: 5px; font-size: 1.1rem; color: #1e293b;">🚗 Pilih Mobil</h3>
                    <p style="color: #64748b; font-size: 0.9rem;">Pilih kendaraan sesuai kebutuhan kapasitas dan budget.</p>
                </div>
            </div>
            <div class="step-box">
                <div class="step-number">02</div>
                <div class="step-content">
                    <h3 style="margin-top: 5px; font-size: 1.1rem; color: #1e293b;">📅 Jadwal Rental</h3>
                    <p style="color: #64748b; font-size: 0.9rem;">Masukkan tanggal keberangkatan dan durasi pemakaian.</p>
                </div>
            </div>
            <div class="step-box">
                <div class="step-number">03</div>
                <div class="step-content">
                    <h3 style="margin-top: 5px; font-size: 1.1rem; color: #1e293b;">📝 Isi Data Diri</h3>
                    <p style="color: #64748b; font-size: 0.9rem;">Lengkapi data diri dan persetujuan penyewaan dalam order
                        form.</p>
                </div>
            </div>
            <div class="step-box">
                <div class="step-number">04</div>
                <div class="step-content">
                    <h3 style="margin-top: 5px; font-size: 1.1rem; color: #1e293b;">🔍 Konfirmasi</h3>
                    <p style="color: #64748b; font-size: 0.9rem;">Tunggu konfirmasi admin kami terkait ketersediaan 100%.
                    </p>
                </div>
            </div>
            <div class="step-box">
                <div class="step-number">05</div>
                <div class="step-content">
                    <h3 style="margin-top: 5px; font-size: 1.1rem; color: #1e293b;">💳 Pembayaran</h3>
                    <p style="color: #64748b; font-size: 0.9rem;">Upload bukti transfer Deposit / Lunas untuk garansi
                        pesanan.</p>
                </div>
            </div>
            <div class="step-box">
                <div class="step-number">06</div>
                <div class="step-content">
                    <h3 style="margin-top: 5px; font-size: 1.1rem; color: #1e293b;">🟢 Mobil Siap</h3>
                    <p style="color: #64748b; font-size: 0.9rem;">Pesanan terkunci. Kunci mobil siap diserahterimakan pada
                        hari H.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- HARGA / PAKET RENTAL -->
    <div class="features" style="background-color: #fff;">
        <h2>Harga Rental Transparan</h2>
        <div style="max-width: 800px; margin: 0 auto;">
            <table class="pricing-table">
                <thead>
                    <tr>
                        <th>Paket Rental</th>
                        <th>Estimasi Tarif</th>
                        <th>Fasilitas Termasuk</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Lepas Kunci (Self-Drive)</strong></td>
                        <td>Mulai Rp250.000/hari</td>
                        <td>Penggunaan Kendaraan Penuh s.d. 24 Jam.</td>
                    </tr>
                    <tr>
                        <td><strong>Dengan Driver Profesional</strong></td>
                        <td>Mulai Rp400.000/hari</td>
                        <td>Kendaraan + Jasa Driver (Dalam Kota, max 12 Jam).</td>
                    </tr>
                    <tr>
                        <td><strong>Mingguan (Weekly)</strong></td>
                        <td><i>Hubungi Admin</i></td>
                        <td>Harga Khusus / Diskon untuk korporasi & pribadi.</td>
                    </tr>
                    <tr>
                        <td><strong>Bulanan (Monthly)</strong></td>
                        <td><i>Hubungi Admin</i></td>
                        <td>Penawaran Spesial Maintenance & Asuransi Tertentu.</td>
                    </tr>
                </tbody>
            </table>
            <p style="text-align: left; font-size: 0.95rem; color: #64748b; margin-top: 15px;">* <b>Catatan:</b> Harga akhir
                dapat berbeda berdasarkan pilihan spesifik unit kendaraan, musim sibuk (High Season), dan lokasi
                penjemputan.</p>
        </div>
    </div>

    <!-- AREA LAYANAN -->
    <div class="features features-bg">
        <h2>Area Layanan Operasional</h2>
        <p style="text-align:center; max-width: 600px; margin: -10px auto 25px auto; color: #64748b; font-size: 1.1rem;">
            Kami melayani penyewaan kendaraan untuk mempermudah mobilitas Anda di wilayah berikut.</p>
        <div class="area-list">
            <span class="area-badge">📍 Surabaya</span>
            <span class="area-badge">📍 Gresik</span>
            <span class="area-badge">📍 Sidoarjo</span>
            <span class="area-badge">📍 Mojokerto</span>
            <span class="area-badge">📍 Lamongan</span>
        </div>
        <div
            style="text-align:center; margin-top: 30px; font-weight: bold; color: #1e293b; display: inline-flex; align-items: center; justify-content: center; background: white; padding: 15px 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
            <span style="font-size: 1.5rem; margin-right: 15px;">🚗</span>
            Antar-jemput kendaraan tersedia sesuai area layanan (T&C Apply).
        </div>
    </div>

    <!-- TESTIMONI PELANGGAN -->
    <div class="features" style="background-color: #fff;">
        <h2>Apa Kata Pelanggan Kami?</h2>
        <p style="text-align:center; max-width: 600px; margin: -20px auto 30px auto; color: #64748b; font-size: 1.1rem;">
            Pengalaman nyata ratusan pelanggan yang telah mempercayakan perjalanan mereka.</p>
        <div class="grid">
            @forelse($reviews ?? [] as $review)
                <div class="testimonial-card">
                    <div class="stars">{{ str_repeat('⭐', $review->rating) }}</div>
                    <h4 style="margin-top:0; color:#1e293b; font-size: 1.1rem;">Ulasan Terverifikasi</h4>
                    <p style="color: #475569; font-style: italic; line-height: 1.6;">"{{ $review->comment }}"</p>
                    <div style="margin-top: 15px;">
                        <p style="font-weight: 700; margin-bottom: 0; color:#1e293b;">{{ $review->user->name ?? 'Customer' }}</p>
                        <p style="font-size: 0.85rem; color: #94a3b8; margin-top: 2px;">Customer RentalMobil</p>
                    </div>
                </div>
            @empty
                <div class="testimonial-card">
                    <div class="stars">⭐⭐⭐⭐⭐</div>
                    <h4 style="margin-top:0; color:#1e293b; font-size: 1.1rem;">Pelayanan Memuaskan</h4>
                    <p style="color: #475569; font-style: italic; line-height: 1.6;">"Mobil bersih, sangat wangi dan proses booking cepat sekali. Admin juga responsif membantu perubahan jadwal. Sangat recommended."</p>
                    <div style="margin-top: 15px;">
                        <p style="font-weight: 700; margin-bottom: 0; color:#1e293b;">Andi Pratama</p>
                        <p style="font-size: 0.85rem; color: #94a3b8; margin-top: 2px;">Pegawai Swasta, Surabaya</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- PERSYARATAN RENTAL -->
    <div class="features features-bg">
        <h2>Persyaratan Dokumen Sewa</h2>
        <div
            style="max-width: 600px; margin: 0 auto; text-align: left; background: white; padding: 30px; border-radius: 12px; border: 1px solid #cbd5e1; box-shadow: 0 10px 25px rgba(0,0,0,0.05);">
            <h3
                style="margin-top: 0; color: #1e293b; border-bottom: 2px solid #e2e8f0; padding-bottom: 15px; margin-bottom: 20px;">
                Untuk Sewa Lepas Kunci:</h3>
            <ul style="list-style: none; padding: 0; line-height: 2.2; color: #334155; font-size: 1.05rem;">
                <li style="display: flex; gap: 15px; border-bottom: 1px solid #f1f5f9;"><span
                        style="color: #10b981;">✅</span> E-KTP / ID Card Resmi</li>
                <li style="display: flex; gap: 15px; border-bottom: 1px solid #f1f5f9;"><span
                        style="color: #10b981;">✅</span> SIM A yang Masih Aktif</li>
                <li style="display: flex; gap: 15px; border-bottom: 1px solid #f1f5f9;"><span
                        style="color: #10b981;">✅</span> Data Kontak / Sosial Media Aktif</li>
                <li style="display: flex; gap: 15px; border-bottom: 1px solid #f1f5f9;"><span
                        style="color: #10b981;">✅</span> Dokumen Pendukung Lain (Opsional sesuai ketentuan)</li>
                <li style="display: flex; gap: 15px;"><span style="color: #10b981;">✅</span> Dana Deposit / Jaminan Unit
                    Terkait</li>
            </ul>
        </div>
    </div>

    <!-- FAQ -->
    <div class="features" id="faq" style="background-color: #fff;">
        <h2>Pertanyaan Umum (FAQ)</h2>
        <div style="max-width: 800px; margin: 0 auto; text-align: left;">
            <div style="margin-bottom: 20px; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px;">
                <h4 style="margin-top: 0; color: #1e293b;">Apakah harus memiliki SIM?</h4>
                <p style="color: #64748b; margin-bottom: 0;">Ya, Customer wajib memiliki SIM A yang masih berlaku dan KTP
                    asli saat proses serah terima kendaraan.</p>
            </div>
            <div style="margin-bottom: 20px; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px;">
                <h4 style="margin-top: 0; color: #1e293b;">Apakah ada deposit per unit mobil?</h4>
                <p style="color: #64748b; margin-bottom: 0;">Ya, deposit diwajibkan sebagai jaminan kerugian kecil di mana
                    jumlahnya bervariasi bergantung jenis mobil. Dana deposit akan dikembalikan saat mobil kembali dalam
                    kondisi baik.</p>
            </div>
            <div style="margin-bottom: 20px; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px;">
                <h4 style="margin-top: 0; color: #1e293b;">Bagaimana pertanggungan jika terjadi kerusakan/baret?</h4>
                <p style="color: #64748b; margin-bottom: 0;">Segala kerusakan yang terjadi selama masa periode sewa
                    sepenuhnya menjadi tanggung jawab Customer sesuai standar pemeriksaan saat Return.</p>
            </div>
        </div>
    </div>

    <!-- CTA BOTTOM -->
    <div class="cta-section">
        <h2>Masih Bingung Memilih Mobil?</h2>
        <p>Konsultasikan kebutuhan rental Anda dengan tim ahli kami secara gratis.<br>Kami siap membantu Anda memilih
            kendaraan sesuai prioritas perjalanan dan budget terbaik.</p>
        <div class="cta-buttons">
            <a href="https://wa.me/6285748174062?text=Halo,%20saya%20butuh%20rekomendasi%20mobil%20untuk%20disewa."
                class="btn btn-outline"
                style="background: white; color: #1e3a8a; border-color: white; font-weight:bold; display: flex; align-items:center; gap: 8px;">
                💬 Chat WhatsApp
            </a>
            <a href="/armada" class="btn btn-primary" style="background: #2563eb; border-color: #2563eb; font-weight:bold;">
                🚗 Lihat Armada
            </a>
        </div>
    </div>

@endsection