@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')
    <div style="background-color: #1e293b; padding: 60px 20px; text-align: center; color: white;">
        <h1 style="margin: 0; font-size: 2.5rem;">Katalog Armada Kami</h1>
        <p style="margin-top: 10px; color: #cbd5e1;">Temukan berbagai pilihan mobil terbaik untuk perjalanan Anda.</p>
    </div>

    <!-- Quick Search Widget -->
    <div class="search-widget"
        style="max-width: 900px; margin: -30px auto 40px auto; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); position: relative; z-index: 10;">
        <form action="/armada" method="GET"
            style="display: flex; gap: 15px; flex-wrap: wrap; justify-content: center; align-items: flex-end;">
            <div style="flex: 1; min-width: 200px;">
                <label
                    style="display:block; font-size: 0.85rem; font-weight: bold; margin-bottom: 5px; color: #64748b;">Mulai
                    Sewa</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" required class="form-control-cs"
                    style="width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px;">
            </div>
            <div style="flex: 1; min-width: 200px;">
                <label
                    style="display:block; font-size: 0.85rem; font-weight: bold; margin-bottom: 5px; color: #64748b;">Selesai
                    Sewa</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" required class="form-control-cs"
                    style="width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px;">
            </div>
            <div style="flex: 1; min-width: 200px;">
                <button type="submit" class="btn btn-primary"
                    style="width: 100%; padding: 12px; border-radius: 8px; height: 43px;">Terapkan Filter / Cari
                    &rsaquo;</button>
            </div>
            @if(request('start_date'))
                <div style="flex-basis: 100%; text-align: center; margin-top: 10px;">
                    <a href="/armada" style="color: #ef4444; font-size: 0.85rem; text-decoration: underline;">Hapus Filter</a>
                </div>
            @endif
        </form>
    </div>

    <div class="features" style="padding-top: 10px;">
        <div class="grid">
            @forelse($cars as $car)
                <div class="card" style="position: relative;">
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

                    <img src="{{ $car->image_path ?: 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&q=80&w=600' }}"
                        onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&q=80&w=600'"
                        alt="{{ $car->name }}">
                    <h3>{{ $car->brand }} {{ $car->name }}</h3>
                    <p class="car-meta-text">Tahun {{ $car->year }} &bull; Plat: {{ $car->license_plate }}</p>

                    @if($car->description)
                        <p style="font-size: 0.85rem; color: #64748b; margin-top: 5px; margin-bottom: 10px; line-height: 1.4;">
                            {{ \Illuminate\Support\Str::limit($car->description, 100) }}
                        </p>
                    @endif

                    <div
                        style="display: flex; gap: 15px; margin-top: 5px; margin-bottom: 15px; font-size: 0.85rem; color: #475569;">
                        <span>👤 {{ $car->seats }} Kursi</span>
                        <span>🧳 {{ $car->luggage }} Koper</span>
                    </div>

                    <div class="price">Rp {{ number_format($car->price_per_day, 0, ',', '.') }}<span
                            class="price-suffix">/hari</span></div>

                    <div style="margin-top: 15px; border-top: 1px solid #e2e8f0; padding-top: 10px;">
                        <p style="font-size: 0.8rem; font-weight: bold; margin-bottom: 5px;">Daftar Fasilitas:</p>
                        <ul
                            style="list-style: none; padding: 0; margin: 0; font-size: 0.8rem; color: #475569; display: flex; flex-direction: column; gap: 4px;">
                            @php
                                $carFacilities = is_array($car->facilities) ? $car->facilities : [];
                                $allFacilities = \App\Models\Facility::orderBy('id')->pluck('name')->toArray();
                            @endphp
                            @foreach($allFacilities as $facility)
                                @if(in_array($facility, $carFacilities))
                                    <li>✅ {{ $facility }}</li>
                                @else
                                    @if($facility == 'Lepas Kunci')
                                        <li>❌ Tidak Melayani Lepas Kunci</li>
                                    @else
                                        <li>❌ Tidak {{ $facility }}</li>
                                    @endif
                                @endif
                            @endforeach
                        </ul>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 8px; margin-top: 15px;">
                        @php
                            $waText = urlencode("Halo, saya ingin menanyakan mobil {$car->brand} {$car->name}. Apakah masih tersedia untuk disewa?");
                        @endphp
                        <a href="https://wa.me/6285748174062?text={{ $waText }}" target="_blank"
                            class="btn btn-outline btn-full-width"
                            style="display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 0.9rem;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="#25D366" stroke="#25D366" stroke-width="0">
                                <path
                                    d="M12.01 2.01c-5.46 0-9.89 4.43-9.89 9.89 0 1.74.45 3.4 1.3 4.88L2 22l5.44-1.42c1.44.78 3.06 1.22 4.57 1.22 5.46 0 9.89-4.43 9.89-9.89 0-5.46-4.43-9.89-9.89-9.89zM12.01 19.98c-1.47 0-2.91-.39-4.18-1.14l-.3-.18-3.1.81.82-3.03-.2-.31c-.82-1.28-1.25-2.76-1.25-4.28 0-4.46 3.63-8.08 8.08-8.08 4.46 0 8.08 3.63 8.08 8.08s-3.63 8.08-8.08 8.08zm4.43-6.07c-.24-.12-1.44-.71-1.66-.79-.22-.08-.38-.12-.54.12-.16.24-.62.79-.76.95-.14.16-.28.18-.52.06-1.25-.63-2.39-1.42-3.19-2.35-.22-.26.01-.24.23-.68.08-.16.04-.3-.02-.42-.06-.12-.54-1.3-.74-1.78-.19-.47-.38-.41-.54-.41-.16 0-.34-.02-.5-.02s-.42.06-.64.3c-.22.24-.84.82-.84 2.01 0 1.19.86 2.34.98 2.5.12.16 1.7 2.61 4.14 3.65.58.25 1.03.4 1.38.51.58.19 1.12.16 1.54.1.47-.07 1.44-.59 1.64-1.16.2-.57.2-1.06.14-1.16-.06-.1-.22-.16-.46-.28z" />
                            </svg>
                            Tanya via WhatsApp
                        </a>
                        <a href="{{ route('bookings.create', $car->id) }}" class="btn btn-primary btn-full-width">🚗 Sewa
                            Sekarang</a>
                    </div>
                </div>
            @empty
                <div class="card empty-car-card" style="grid-column: 1 / -1;">
                    <p class="empty-car-text">Tidak ada kendaraan yang sesuai dengan filter pencarian Anda.</p>
                </div>
            @endforelse
        </div>

        <div style="margin-top: 40px; display: flex; justify-content: center;">
            {{ $cars->links() }}
        </div>
    </div>
@endsection