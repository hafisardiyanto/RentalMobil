@extends('layouts.app')

@section('content')
<style>
    .booking-container {
        padding: 5rem 5%;
        background: var(--background);
        display: flex;
        justify-content: center;
        gap: 3rem;
    }

    .booking-card {
        background: white;
        padding: 2.5rem;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.05);
        width: 100%;
        max-width: 500px;
    }

    .car-summary {
        background: white;
        padding: 2.5rem;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.05);
        width: 100%;
        max-width: 400px;
        height: fit-content;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--text-main);
    }

    .form-control {
        width: 100%;
        padding: 1rem;
        border: 2px solid #E2E8F0;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }

    .price-total {
        margin-top: 2rem;
        padding-top: 1rem;
        border-top: 2px dashed #E2E8F0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .total-amount {
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--accent);
    }
</style>

<div class="booking-container">
    <div class="car-summary">
        <h2 style="margin-bottom: 1.5rem;">Ringkasan Armada</h2>
        <img src="{{ $car->image_path ?? 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&q=80&w=600' }}" 
             style="width: 100%; border-radius: 12px; margin-bottom: 1.5rem;" alt="{{ $car->name }}">
        <h3>{{ $car->brand }} {{ $car->name }}</h3>
        <p style="color: var(--text-muted);">Tahun: {{ $car->year }}</p>
        <p style="color: var(--primary); font-weight: 700; margin-top: 0.5rem;">
            Rp {{ number_format($car->price_per_day, 0, ',', '.') }} / hari
        </p>
    </div>

    <div class="booking-card">
        <h2 style="margin-bottom: 2rem;">Lengkapi Detail Sewa</h2>
        <form action="{{ route('bookings.store') }}" method="POST" id="bookingForm">
            @csrf
            <input type="hidden" name="car_id" value="{{ $car->id }}">
            <input type="hidden" id="price_per_day" value="{{ $car->price_per_day }}">

            <div class="form-group">
                <label for="start_date">Tanggal Mulai Sewa</label>
                <input type="date" id="start_date" name="start_date" class="form-control" 
                       min="{{ date('Y-m-d') }}" required onchange="calculatePrice()">
            </div>

            <div class="form-group">
                <label for="end_date">Tanggal Selesai Sewa</label>
                <input type="date" id="end_date" name="end_date" class="form-control" 
                       min="{{ date('Y-m-d') }}" required onchange="calculatePrice()">
            </div>

            <div class="price-total">
                <span style="font-weight: 600; color: var(--text-muted);">Total Tagihan:</span>
                <span class="total-amount" id="total_price_label">Rp 0</span>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 2rem; padding: 1.2rem;">
                Konfirmasi Sewa Sekarang
            </button>
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
