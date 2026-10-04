@extends('layouts.admin')

@push('admin_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/crm.css') }}">
@endpush

@section('content')
    <div class="crm-card">
        <div class="crm-header-flex">
            <h2 class="crm-title">👥 Manajemen Pelanggan (CRM & Loyalty)</h2>
            <span style="color:#64748b; font-size:0.9rem;">Total Pelanggan: <b>{{ $customers->count() }}
                    Terdaftar</b></span>
        </div>

        <div
            style="margin-bottom:20px; background:#f0f9ff; padding:15px; border-radius:8px; border:1px solid #bae6fd; font-size:0.9rem; color:#0369a1;">
            ℹ️ <b>Info Perhitungan Poin:</b> Sistem otomatis memberikan <b>1 Poin Loyalitas</b> untuk setiap kelipatan
            pembelanjaan Rp 100.000 pada transaksi yang telah <i>Selesai</i>. Pelanggan meraih predikat VIP secara
            berjenjang berdasarkan akumulasi mutasi.
        </div>

        <div style="overflow-x:auto;">
            <table class="crm-table">
                <thead>
                    <tr>
                        <th>Pelanggan</th>
                        <th>Kontak / Email</th>
                        <th>Status Loyalitas</th>
                        <th>Poin</th>
                        <th>Order Sukses</th>
                        <th>Total Belanja (Gross)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        <tr>
                            <td>
                                <div style="font-weight: 700; color:#1e293b;">{{ $customer->name }}</div>
                                <small style="color: #64748b;">NIK: {{ $customer->nik ?: 'Belum diisi' }}</small>
                            </td>
                            <td>
                                <span style="color:#2563eb; text-decoration:underline;">{{ $customer->email }}</span><br>
                                <small>{{ $customer->phone ?: 'No HP Kosong' }}</small>
                            </td>
                            <td>
                                @if($customer->vip_status === 'Gold')
                                    <span class="crm-badge-gold">🏅 Premium GOLD</span>
                                @elseif($customer->vip_status === 'Silver')
                                    <span class="crm-badge-silver">🥈 Favorit SILVER</span>
                                @else
                                    <span class="crm-badge-reguler">Standar</span>
                                @endif
                            </td>
                            <td align="center">
                                <span class="crm-points">{{ number_format($customer->loyalty_points, 0, ',', '.') }}</span>
                            </td>
                            <td align="center">
                                {{ $customer->total_rentals }}x Sewa
                            </td>
                            <td>
                                <span class="crm-money">Rp {{ number_format($customer->total_spent, 0, ',', '.') }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: #64748b;">Belum ada Data Pelanggan yang Terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection