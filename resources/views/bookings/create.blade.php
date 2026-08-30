@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/customer-booking.css') }}">
@endpush

@section('content')

    <div class="booking-container-cs">
        <div class="car-summary-cs">
            <h2 class="cs-margin-bottom">Ringkasan Armada</h2>
            <img src="{{ $car->image_path ?? 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&q=80&w=600' }}"
                class="cs-car-img" alt="{{ $car->name }}">
            <h3>{{ $car->brand }} {{ $car->name }}</h3>
            <p class="cs-text-muted">Tahun: {{ $car->year }}</p>
            <p class="cs-price-text">
                Rp {{ number_format($car->price_per_day, 0, ',', '.') }} / hari
            </p>
        </div>

        <div class="booking-card-cs">
            <h2 class="cs-margin-bottom-lg">Lengkapi Detail Sewa</h2>
            <form action="{{ route('bookings.store') }}" method="POST" id="bookingForm">
                @csrf
                <input type="hidden" name="car_id" value="{{ $car->id }}">
                <input type="hidden" id="price_per_day" value="{{ $car->price_per_day }}">

                <div class="form-group-cs">
                    <label for="start_date">Tanggal Mulai Sewa</label>
                    <input type="date" id="start_date" name="start_date" class="form-control-cs" min="{{ date('Y-m-d') }}"
                        required onchange="calculatePrice()">
                </div>

                <div class="form-group-cs">
                    <label for="end_date">Tanggal Selesai Sewa</label>
                    <input type="date" id="end_date" name="end_date" class="form-control-cs" min="{{ date('Y-m-d') }}"
                        required onchange="calculatePrice()">
                </div>

                <div class="price-total-cs">
                    <span class="cs-tag">Total Tagihan:</span>
                    <span class="total-amount-cs" id="total_price_label">Rp 0</span>
                </div>

                <button type="submit" class="btn btn-primary cs-btn-submit">
                    Konfirmasi Sewa Sekarang
                </button>
                <p class="cs-info-text">
                    <span class="cs-emoji">💬</span> Setelah menekan tombol ini, pesanan Anda akan dicatat ke dalam sistem
                    dan Anda akan diarahkan langsung ke aplikasi WhatsApp untuk berkomunikasi dengan Pemilik Rental.
                </p>
            </form>
        </div>
    </div>

    <script>
        function calculatePrice() {
            const startInput = document.getElementById('start_date').value;
            const endInput = document.getElementById('end_date').value;
            const pricePerDay = parseInt(document.getElementById('price_per_day').value);
            const label = document.getElementById('total_price_label');

            if (startInput && endInput) {
                const start = new Date(startInput);
                const end = new Date(endInput);

                // Minimal 1 hari jika tanggal sama
                const diffTime = Math.max(0, end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) || 1;

                if (end < start) {
                    label.innerHTML = "Tanggal tidak valid";
                    label.style.color = "red";
                    return;
                }

                const total = diffDays * pricePerDay;
                label.innerHTML = "Rp " + total.toLocaleString('id-ID');
                label.style.color = "var(--accent)";
            }
        }
    </script>
@endsection